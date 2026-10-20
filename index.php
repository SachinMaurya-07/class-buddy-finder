<?php
require __DIR__ . '/config.php';
$pageTitle = 'Find your campus circle';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
 <div>
  <div class="eyebrow">Your campus, more connected</div>
  <h1>Make your free periods <span style="color:#c9d4ff">worth it.</span></h1>
  <p>Find classmates who are free when you are. Study together, grab a snack, meet new friends and make campus life better.</p>
  <div class="hero-actions">
   <?php if (!empty($_SESSION['student_id'])): ?>
    <a class="btn btn-white" href="dashboard.php">Open dashboard →</a>
    <a class="btn btn-outline" href="find_buddy.php">Find buddies</a>
   <?php else: ?>
    <a class="btn btn-white" href="register.php">Create free account →</a>
    <a class="btn btn-outline" href="login.php">Student login</a>
   <?php endif; ?>
  </div>
 </div>
 <div class="hero-visual">
  <div class="visual-card">
   <div class="big-emoji">🤝</div><h3>Your next study buddy</h3>
   <p>Shared free period · Same section</p>
   <div class="slot"><span>📅 Wednesday</span><strong>Period 4</strong></div>
   <div class="slot"><span>📍 Campus library</span><strong>Meet up</strong></div>
  </div>
 </div>
</section>
<div class="section-head"><div><h2>Everything for your campus circle</h2><p>A friendly, simple way to connect between classes.</p></div></div>
<section class="feature-grid">
 <article class="feature"><div class="feature-icon">📅</div><h3>Timetable matching</h3><p>Find free periods using the timetable uploaded by your college admin.</p></article>
 <article class="feature"><div class="feature-icon">🧑‍🤝‍🧑</div><h3>Find classmates</h3><p>Discover students in your branch, year and section with matching availability.</p></article>
 <article class="feature"><div class="feature-icon">💬</div><h3>Connect & plan</h3><p>Send friend requests and suggest a time to study or hang out.</p></article>
 <article class="feature"><div class="feature-icon">🔔</div><h3>Stay updated</h3><p>See friend requests, meet-up responses and other notifications in one place.</p></article>
</section>
<div class="section-head"><div><h2>How it works</h2><p>Three steps to turn a free period into a plan.</p></div></div>
<section class="feature-grid">
 <article class="feature"><span class="pill">STEP 01</span><h3 style="margin-top:12px">Create your profile</h3><p>Add your branch, year, section and interests.</p></article>
 <article class="feature"><span class="pill">STEP 02</span><h3 style="margin-top:12px">Find a match</h3><p>See classmates who share your free period.</p></article>
 <article class="feature"><span class="pill">STEP 03</span><h3 style="margin-top:12px">Make a plan</h3><p>Send a friend request or suggest a meet-up.</p></article>
 <article class="feature"><span class="pill">ADMIN MANAGED</span><h3 style="margin-top:12px">Reliable timetable</h3><p>Only administrators can upload or update official timetable data.</p></article>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
