<?php
require __DIR__ . '/config.php'; student_required(); $me=current_student($pdo);
$sql = "SELECT DISTINCT s.id,s.name,s.branch,s.year,s.section,s.bio,s.hobbies,s.interests,
 t.day,t.period,t.start_time,t.end_time
 FROM students s
 JOIN timetables t ON t.branch=s.branch AND t.year=s.year AND t.section=s.section
 JOIN timetables mine ON mine.branch=? AND mine.year=? AND mine.section=? AND mine.day=t.day AND mine.period=t.period
 WHERE s.id<>? AND UPPER(TRIM(COALESCE(t.subject,''))) IN ('','FREE')
 AND UPPER(TRIM(COALESCE(mine.subject,''))) IN ('','FREE')
 ORDER BY FIELD(t.day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'),t.start_time,s.name";
$stmt=$pdo->prepare($sql); $stmt->execute([$me['branch'],$me['year'],$me['section'],$me['id']]); $rows=$stmt->fetchAll();
$matches=[];
foreach($rows as $r){$id=(int)$r['id']; if(!isset($matches[$id])){$matches[$id]=$r;$matches[$id]['slots']=[];} $matches[$id]['slots'][]=$r['day'].' · Period '.$r['period'].' ('.substr($r['start_time'],0,5).')';}
$pageTitle='Find buddies'; require __DIR__.'/includes/header.php';
?>
<div class="section-head"><div><span class="pill">YOUR CAMPUS CIRCLE</span><h2 style="margin-top:8px">Find classmates with free time</h2><p>Matches are based on admin-uploaded timetable periods marked FREE.</p></div></div>
<?php if(!$matches): ?><div class="panel"><h3>No matches found yet</h3><p class="muted">Your timetable may not be uploaded, or no classmates have matching free periods. Check back after your admin uploads the timetable.</p></div>
<?php else: ?><div class="buddy-grid"><?php foreach($matches as $b): ?><article class="buddy-card"><div class="row"><div class="avatar"><?= e(strtoupper(substr($b['name'],0,1))) ?></div><div><span class="pill"><?= e($b['branch']) ?> · Y<?= e($b['year']) ?> · <?= e($b['section']) ?></span><h3><?= e($b['name']) ?></h3></div></div><p><?= e($b['bio'] ?: 'This student has not added a bio yet.') ?></p><?php if($b['hobbies']): ?><p><strong>Hobbies:</strong> <?= e($b['hobbies']) ?></p><?php endif; ?><p><strong>Shared free periods</strong></p><ul style="padding-left:18px;font-size:12px;color:var(--muted)"><?php foreach(array_slice(array_unique($b['slots']),0,5) as $slot): ?><li><?= e($slot) ?></li><?php endforeach; ?></ul>
<form method="post" action="friends.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="send"><input type="hidden" name="receiver_id" value="<?= (int)$b['id'] ?>"><button class="btn btn-primary" type="submit">＋ Add friend</button></form>
<form method="post" action="meetups.php" style="margin-top:8px"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="send"><input type="hidden" name="receiver_id" value="<?= (int)$b['id'] ?>"><input type="hidden" name="meetup_day" value="<?= e($b['day']) ?>"><input type="hidden" name="period" value="<?= e($b['period']) ?>"><input type="hidden" name="message" value="Want to study or hang out during our shared free period?"><button class="btn btn-muted" type="submit">Suggest meet-up</button></form>
</article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__.'/includes/footer.php'; ?>
