<?php
require __DIR__ . '/config.php';
$error='';$success='';
$count=(int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if($count>0){exit('An admin account already exists. Delete setup_admin.php from your server.');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$username=trim((string)($_POST['username']??''));$password=(string)($_POST['password']??'');
 if(strlen($username)<4||strlen($password)<12)$error='Username must be at least 4 characters and password at least 12 characters.';
 else{$s=$pdo->prepare('INSERT INTO admins(username,password) VALUES(?,?)');$s->execute([$username,password_hash($password,PASSWORD_DEFAULT)]);$success='Admin created. Delete setup_admin.php now, then sign in at admin/login.php.';}
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Initial admin setup</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="page"><div class="form-wrap"><form class="form-card" method="post"><h1>Initial admin setup</h1><p class="subtle">Use this once, then delete this file.</p><?php if($error):?><div class="alert"><?=e($error)?></div><?php endif;?><?php if($success):?><div class="alert success"><?=e($success)?></div><?php endif;?><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><div class="field"><label>Admin username</label><input name="username" required minlength="4"></div><div class="field"><label>Strong password (12+ characters)</label><input type="password" name="password" required minlength="12"></div><button class="btn btn-primary">Create admin</button></form></div></main></body></html>
