<?php
require __DIR__.'/config.php';student_required();$me=current_student($pdo);$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$action=(string)($_POST['action']??'');
 if($action==='send'){
  $receiver=(int)($_POST['receiver_id']??0);$day=trim((string)($_POST['meetup_day']??''));$period=trim((string)($_POST['period']??''));$body=trim((string)($_POST['message']??''));
  if($receiver!==$me['id']&&in_array($day,['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],true)&&$period!==''){
   $s=$pdo->prepare('INSERT INTO meetups(sender_id,receiver_id,meetup_day,period,message) VALUES(?,?,?,?,?)');$s->execute([$me['id'],$receiver,$day,$period,mb_substr($body,0,300)]);
   notify_user($pdo,$receiver,'Meet-up suggestion',$me['name'].' suggested a meet-up on '.$day.', period '.$period.'.','meetups.php');$message='Meet-up suggestion sent.';
  }
 } elseif(in_array($action,['accept','reject'],true)){
  $id=(int)($_POST['meetup_id']??0);$s=$pdo->prepare("SELECT sender_id FROM meetups WHERE id=? AND receiver_id=? AND status='pending'");$s->execute([$id,$me['id']]);$r=$s->fetch();
  if($r){$status=$action==='accept'?'accepted':'rejected';$u=$pdo->prepare('UPDATE meetups SET status=? WHERE id=?');$u->execute([$status,$id]);notify_user($pdo,(int)$r['sender_id'],'Meet-up '.$status,$me['name'].' '.$status.' your meet-up suggestion.','meetups.php');$message='Meet-up response saved.';}
 }
}
$in=$pdo->prepare("SELECT m.*,s.name FROM meetups m JOIN students s ON s.id=m.sender_id WHERE m.receiver_id=? ORDER BY m.created_at DESC");$in->execute([$me['id']]);$received=$in->fetchAll();
$out=$pdo->prepare("SELECT m.*,s.name FROM meetups m JOIN students s ON s.id=m.receiver_id WHERE m.sender_id=? ORDER BY m.created_at DESC");$out->execute([$me['id']]);$sent=$out->fetchAll();
$pageTitle='Meet-ups';require __DIR__.'/includes/header.php';
?>
<div class="section-head"><div><h2>Meet-up plans</h2><p>Suggest a study session or a quick break between classes.</p></div><a class="btn btn-primary" href="find_buddy.php">Find a buddy</a></div>
<?php if($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
<div class="panel"><h3>Requests for you</h3><?php if(!$received): ?><p class="muted">No meet-up suggestions yet.</p><?php else: foreach($received as $m): ?><div class="notice"><div class="between"><div><strong><?= e($m['name']) ?></strong><p class="subtle"><?= e($m['meetup_day']) ?> · Period <?= e($m['period']) ?> · <?= e($m['message']) ?></p><span class="pill"><?= e($m['status']) ?></span></div><?php if($m['status']==='pending'): ?><div class="row"><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="meetup_id" value="<?= (int)$m['id'] ?>"><button class="btn btn-primary" name="action" value="accept">Accept</button></form><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="meetup_id" value="<?= (int)$m['id'] ?>"><button class="btn btn-muted" name="action" value="reject">Reject</button></form></div><?php endif; ?></div></div><?php endforeach; endif; ?></div>
<div class="panel"><h3>Suggestions you sent</h3><?php if(!$sent): ?><p class="muted">You haven't suggested a meet-up yet.</p><?php else: foreach($sent as $m): ?><div class="notice"><strong><?= e($m['name']) ?></strong><p class="subtle"><?= e($m['meetup_day']) ?> · Period <?= e($m['period']) ?> · <?= e($m['message']) ?></p><span class="pill"><?= e($m['status']) ?></span></div><?php endforeach; endif; ?></div>
<?php require __DIR__.'/includes/footer.php'; ?>
