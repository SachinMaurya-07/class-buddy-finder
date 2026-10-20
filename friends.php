<?php
require __DIR__.'/config.php'; student_required(); $me=current_student($pdo); $message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $action=(string)($_POST['action']??''); $id=(int)($_POST['request_id']??0);
 if($action==='send'){
  $receiver=(int)($_POST['receiver_id']??0);
  if($receiver!==$me['id']){
   $check=$pdo->prepare('SELECT id FROM students WHERE id=?');$check->execute([$receiver]);
   if($check->fetch()){
    $exists=$pdo->prepare('SELECT id FROM friend_requests WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?)');
    $exists->execute([$me['id'],$receiver,$receiver,$me['id']]);
    if(!$exists->fetch()){
     $s=$pdo->prepare("INSERT INTO friend_requests(sender_id,receiver_id,status) VALUES(?,?,'pending')");$s->execute([$me['id'],$receiver]);
     notify_user($pdo,$receiver,'New friend request',$me['name'].' sent you a friend request.','friends.php');$message='Friend request sent.';
    } else $message='A request or friendship already exists.';
   }
  }
 } elseif(in_array($action,['accept','reject'],true)){
  $s=$pdo->prepare("SELECT sender_id FROM friend_requests WHERE id=? AND receiver_id=? AND status='pending'");$s->execute([$id,$me['id']]);$req=$s->fetch();
  if($req){$status=$action==='accept'?'accepted':'rejected';$u=$pdo->prepare('UPDATE friend_requests SET status=? WHERE id=?');$u->execute([$status,$id]);notify_user($pdo,(int)$req['sender_id'],'Friend request '.$status,$me['name'].' '.$status.' your friend request.','friends.php');$message='Request updated.';}
 }
}
$in=$pdo->prepare("SELECT fr.id,fr.created_at,s.id AS student_id,s.name,s.branch,s.year,s.section FROM friend_requests fr JOIN students s ON s.id=fr.sender_id WHERE fr.receiver_id=? AND fr.status='pending' ORDER BY fr.created_at DESC");$in->execute([$me['id']]);$incoming=$in->fetchAll();
$out=$pdo->prepare("SELECT s.name,s.branch,s.year,s.section,fr.status,fr.created_at FROM friend_requests fr JOIN students s ON s.id=fr.receiver_id WHERE fr.sender_id=? ORDER BY fr.created_at DESC");$out->execute([$me['id']]);$outgoing=$out->fetchAll();
$friends=$pdo->prepare("SELECT DISTINCT s.id,s.name,s.branch,s.year,s.section FROM friend_requests fr JOIN students s ON (s.id=fr.sender_id AND fr.receiver_id=?) OR (s.id=fr.receiver_id AND fr.sender_id=?) WHERE fr.status='accepted' AND s.id<>? ORDER BY s.name");$friends->execute([$me['id'],$me['id'],$me['id']]);$friendList=$friends->fetchAll();
$pageTitle='Friends';require __DIR__.'/includes/header.php';
?>
<div class="section-head"><div><h2>Friends & requests</h2><p>Build your campus circle.</p></div><a class="btn btn-primary" href="find_buddy.php">Find classmates</a></div>
<?php if($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
<div class="panel"><h3>Incoming requests</h3><?php if(!$incoming): ?><p class="muted">No pending friend requests.</p><?php else: foreach($incoming as $r): ?><div class="notice"><div class="between"><div><strong><?= e($r['name']) ?></strong><div class="subtle"><?= e($r['branch']) ?> · Year <?= e($r['year']) ?> · Section <?= e($r['section']) ?></div></div><div class="row"><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="request_id" value="<?= (int)$r['id'] ?>"><button class="btn btn-primary" name="action" value="accept">Accept</button></form><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="request_id" value="<?= (int)$r['id'] ?>"><button class="btn btn-muted" name="action" value="reject">Reject</button></form></div></div></div><?php endforeach; endif; ?></div>
<div class="panel"><h3>Your friends</h3><?php if(!$friendList): ?><p class="muted">Your accepted connections will appear here.</p><?php else: foreach($friendList as $f): ?><div class="notice"><strong><?= e($f['name']) ?></strong><div class="subtle"><?= e($f['branch']) ?> · Year <?= e($f['year']) ?> · Section <?= e($f['section']) ?></div></div><?php endforeach; endif; ?></div>
<div class="panel"><h3>Requests you sent</h3><?php if(!$outgoing): ?><p class="muted">No outgoing requests yet.</p><?php else: ?><div class="table-wrap"><table class="data-table"><thead><tr><th>Name</th><th>Status</th><th>Sent</th></tr></thead><tbody><?php foreach($outgoing as $r): ?><tr><td><?= e($r['name']) ?></td><td><span class="pill"><?= e($r['status']) ?></span></td><td><?= e($r['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
<?php require __DIR__.'/includes/footer.php'; ?>
