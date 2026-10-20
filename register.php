<?php
require __DIR__ . '/config.php';
if (!empty($_SESSION['student_id'])) redirect('dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $branch = trim((string)($_POST['branch'] ?? ''));
    $year = trim((string)($_POST['year'] ?? ''));
    $section = strtoupper(trim((string)($_POST['section'] ?? '')));
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $branch === '' || $year === '' || $section === '') {
        $error = 'Please complete all fields. Password must be at least 8 characters.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO students (name,email,password,branch,year,section) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$name,$email,password_hash($password, PASSWORD_DEFAULT),$branch,$year,$section]);
            session_regenerate_id(true);
            $_SESSION['student_id'] = (int)$pdo->lastInsertId();
            redirect('dashboard.php');
        } catch (PDOException $ex) {
            $error = $ex->getCode() === '23000' ? 'This email is already registered. Please log in.' : 'Could not create your account. Please try again.';
        }
    }
}
$pageTitle = 'Create account';
require __DIR__ . '/includes/header.php';
?>
<div class="form-wrap"><form class="form-card" method="post">
<h1>Create your account</h1><p class="subtle">Join your campus circle and find your people.</p>
<?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="field"><label>Full name</label><input name="name" maxlength="100" required value="<?= e($_POST['name'] ?? '') ?>" autocomplete="name"></div>
<div class="field"><label>College email</label><input type="email" name="email" maxlength="190" required value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email"></div>
<div class="field"><label>Password (minimum 8 characters)</label><input type="password" name="password" minlength="8" required autocomplete="new-password"></div>
<div class="form-grid">
<div class="field"><label>Branch</label><select name="branch" required><option value="">Select branch</option><?php foreach(['CSE','CSE-DS','IT','EC','EE','Mechanical','Civil'] as $b): ?><option value="<?= e($b) ?>" <?= (($_POST['branch'] ?? '')===$b?'selected':'') ?>><?= e($b) ?></option><?php endforeach; ?></select></div>
<div class="field"><label>Year</label><select name="year" required><option value="">Select year</option><?php foreach(['1','2','3','4'] as $y): ?><option value="<?= $y ?>" <?= (($_POST['year'] ?? '')===$y?'selected':'') ?>>Year <?= $y ?></option><?php endforeach; ?></select></div>
</div>
<div class="field"><label>Section</label><select name="section" required><option value="">Select section</option><?php foreach(range('A','T') as $s): ?><option value="<?= $s ?>" <?= (($_POST['section'] ?? '')===$s?'selected':'') ?>>Section <?= $s ?></option><?php endforeach; ?></select></div>
<button class="btn btn-primary" type="submit">Create account</button>
<p class="subtle" style="text-align:center;margin-top:18px">Already registered? <a href="login.php">Log in</a></p>
</form></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
