<?php
require dirname(__DIR__).'/config.php';admin_required();
if($_SERVER['REQUEST_METHOD']!=='POST'){redirect('dashboard.php');}
verify_csrf();
if(empty($_FILES['timetable_csv'])||$_FILES['timetable_csv']['error']!==UPLOAD_ERR_OK){redirect('dashboard.php?msg='.urlencode('CSV upload failed. Please try again.'));}
if($_FILES['timetable_csv']['size']>2*1024*1024){redirect('dashboard.php?msg='.urlencode('CSV must be smaller than 2 MB.'));}
$fh=fopen($_FILES['timetable_csv']['tmp_name'],'r');
if(!$fh)redirect('dashboard.php?msg='.urlencode('Could not read CSV.'));
$header=fgetcsv($fh);$expected=['branch','year','section','day','period','start_time','end_time','subject'];
$header=array_map(fn($v)=>strtolower(trim((string)$v)), $header?:[]);
if($header!==$expected){fclose($fh);redirect('dashboard.php?msg='.urlencode('CSV header does not match the required format. Download sample_timetable.csv.'));}
$rows=[];$line=1;$validDays=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];$errors=[];
while(($data=fgetcsv($fh))!==false){$line++;if(count($data)!==8){$errors[]="Line $line: expected 8 columns.";continue;}
 [$branch,$year,$section,$day,$period,$start,$end,$subject]=array_map(fn($v)=>trim((string)$v),$data);
 $day=ucfirst(strtolower($day));$section=strtoupper($section);
 if(!$branch||!$year||!$section||!in_array($day,$validDays,true)||!$period||!preg_match('/^\d{1,2}:\d{2}$/',$start)||!preg_match('/^\d{1,2}:\d{2}$/',$end)||$start>=$end){$errors[]="Line $line: invalid field or time.";continue;}
 $rows[]=[$branch,$year,$section,$day,$period,$start,$end,$subject];
}
fclose($fh);
if($errors||!$rows){$msg=$errors?implode(' ',$errors):'CSV has no data rows.';redirect('dashboard.php?msg='.urlencode(substr($msg,0,500)));}
try{
 $pdo->beginTransaction();
 $groups=[];
 foreach($rows as $r)$groups[$r[0].'|'.$r[1].'|'.$r[2].'|'.$r[3]]=[$r[0],$r[1],$r[2],$r[3]];
 $del=$pdo->prepare('DELETE FROM timetables WHERE branch=? AND year=? AND section=? AND day=?');
 foreach($groups as $g)$del->execute($g);
 $ins=$pdo->prepare('INSERT INTO timetables(branch,year,section,day,period,start_time,end_time,subject) VALUES(?,?,?,?,?,?,?,?)');
 foreach($rows as $r)$ins->execute($r);
 $pdo->commit();
 redirect('dashboard.php?msg='.urlencode('Uploaded '.count($rows).' timetable periods successfully.'));
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();redirect('dashboard.php?msg='.urlencode('Upload failed. Check for duplicate periods or invalid data.'));}
