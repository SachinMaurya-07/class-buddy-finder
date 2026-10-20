<?php
require __DIR__ . '/config.php'; student_required(); $me = current_student($pdo); $message = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf();
 $name=trim((string)($_POST['name']??'')); $bio=trim((string)($_POST['bio']??'')); $hobbies=trim((string)($_POST['hobbies']??'')); $interests=trim((string)($_POST['interests']??''));
 if ($name==='') $message='Name is required.';
 else {
  $s=$pdo->prepare('UPDATE students SET name=?,bio=?,hobbies=?,interests=? WHERE id=?');
  $s->execute([$name,mb_substr($bio,0,500),mb_substr($hobbies,0,255),mb_substr($interests,0,255),$me['id']]);
  $message='Profile updated successfully.'; $me=current_student($pdo);
 }
}
$pageTitle='Edit profile'; require __DIR__.'/includes/header.php';
?>
<div class="form-wrap"><form class="form-card" method="post"><h1>Your profile</h1><p class="subtle">Help classmates get to know you.</p>
<?php if($message): ?><div class="alert success"><?= e($message) ?></div><?php endif; ?>
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label>Name</label><input name="name" maxlength="100" required value="<?= e($me['name']) ?>"></div>
<div class="field"><label>Bio</label><textarea name="bio" maxlength="500" placeholder="A little about you..."><?= e($me['bio']) ?></textarea></div>
<div class="field"><label>Hobbies</label><input name="hobbies" maxlength="255" placeholder="e.g. dancing, coding, riding" value="<?= e($me['hobbies']) ?>"></div>
<div class="field"><label>Interests</label><input name="interests" maxlength="255" placeholder="e.g. startups, AI, football" value="<?= e($me['interests']) ?>"></div>
<div class="field"><label>Class</label><input disabled value="<?= e($me['branch']) ?> · Year <?= e($me['year']) ?> · Section <?= e($me['section']) ?>"><small class="muted">Contact an administrator to correct class details.</small></div>
<button class="btn btn-primary" type="submit">Save profile</button></form></div>
<?php require __DIR__.'/includes/footer.php'; ?>
