<?php
require dirname(__DIR__) . '/config.php';
if(!empty($_SESSION['admin_id']))redirect('dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$username=trim((string)($_POST['username']??''));$password=(string)($_POST['password']??'');
 $s=$pdo->prepare('SELECT id,password FROM admins WHERE username=?');$s->execute([$username]);$a=$s->fetch();
 if($a&&password_verify($password,$a['password'])){session_regenerate_id(true);$_SESSION['admin_id']=(int)$a['id'];redirect('dashboard.php');}
 $error='Username or password is incorrect.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin login · Class Buddy Finder</title><link rel="stylesheet" href="../assets/style.css"></head><body><main class="page"><div class="form-wrap"><form class="form-card" method="post"><h1>Admin login</h1><p class="subtle">Manage official student timetables.</p><?php if($error):?><div class="alert"><?=e($error)?></div><?php endif;?><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><div class="field"><label>Username</label><input name="username" required autocomplete="username"></div><div class="field"><label>Password</label><input type="password" name="password" required autocomplete="current-password"></div><button class="btn btn-primary">Sign in</button><p class="subtle" style="text-align:center"><a href="../index.php">Back to homepage</a></p></form></div></main></body></html>
