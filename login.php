<?php
require __DIR__ . '/config.php';
if (!empty($_SESSION['student_id'])) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT id,password FROM students WHERE email = ?');
    $stmt->execute([$email]); $student = $stmt->fetch();
    if ($student && password_verify($password, $student['password'])) {
        session_regenerate_id(true); $_SESSION['student_id'] = (int)$student['id']; redirect('dashboard.php');
    }
    $error = 'Email or password is incorrect.';
}
$pageTitle = 'Student login';
require __DIR__ . '/includes/header.php';
?>
<div class="form-wrap"><form class="form-card" method="post">
<h1>Welcome back</h1><p class="subtle">Sign in to see who is free when you are.</p>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label>Email</label><input type="email" name="email" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>"></div>
<div class="field"><label>Password</label><input type="password" name="password" required autocomplete="current-password"></div>
<button class="btn btn-primary" type="submit">Log in</button>
<p class="subtle" style="text-align:center;margin-top:18px">New here? <a href="register.php">Create an account</a></p>
</form></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
