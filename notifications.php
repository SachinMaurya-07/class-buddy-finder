<?php
require __DIR__.'/config.php';student_required();$me=current_student($pdo);
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$s=$pdo->prepare('UPDATE notifications SET is_read=1 WHERE user_id=?');$s->execute([$me['id']]);}
$s=$pdo->prepare('SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 100');$s->execute([$me['id']]);$items=$s->fetchAll();
$pageTitle='Notifications';require __DIR__.'/includes/header.php';
?>
<div class="section-head"><div><h2>Notifications</h2><p>Friend requests and meet-up updates in one place.</p></div><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="btn btn-muted" type="submit">Mark all read</button></form></div>
<?php if(!$items): ?><div class="panel"><p class="muted">You're all caught up. Notifications will show here.</p></div><?php else: foreach($items as $n): ?><a href="<?= e($n['url']) ?>" class="notice <?= !$n['is_read']?'unread':'' ?>" style="display:block;color:inherit"><div class="between"><strong><?= e($n['title']) ?></strong><small class="muted"><?= e($n['created_at']) ?></small></div><div class="subtle"><?= e($n['body']) ?></div></a><?php endforeach; endif; ?>
<?php require __DIR__.'/includes/footer.php'; ?>
