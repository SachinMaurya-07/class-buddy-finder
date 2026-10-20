<?php
$pageTitle = $pageTitle ?? APP_NAME;
$unreadCount = 0;
if (!empty($_SESSION['student_id'])) {
    $n = $pdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0');
    $n->execute([(int)$_SESSION['student_id']]);
    $unreadCount = (int)$n->fetchColumn();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0b1220">
<title><?= e($pageTitle) ?> · Class Buddy Finder</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="index.php"><span class="brand-mark">CB</span><span>Class Buddy Finder<small>Find your people between periods</small></span></a>
  <nav class="desktop-nav">
    <a href="index.php">Home</a>
    <?php if (!empty($_SESSION['student_id'])): ?>
      <a href="dashboard.php">Dashboard</a><a href="find_buddy.php">Find buddies</a><a href="notifications.php">Notifications<?= $unreadCount ? ' ('.$unreadCount.')' : '' ?></a><a href="logout.php">Log out</a>
    <?php else: ?>
      <a href="login.php">Login</a><a class="nav-cta" href="register.php">Get started</a>
    <?php endif; ?>
  </nav>
</header>
<main class="page">
