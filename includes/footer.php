</main>
<?php if (!empty($_SESSION['student_id'])): ?>
<nav class="mobile-nav" aria-label="Main navigation">
  <a href="dashboard.php"><span>⌂</span>Home</a>
  <a href="find_buddy.php"><span>♧</span>Buddies</a>
  <a href="friends.php"><span>♡</span>Friends</a>
  <a href="notifications.php"><span>♢</span>Alerts</a>
  <a href="edit_profile.php"><span>◉</span>Profile</a>
</nav>
<?php endif; ?>
<footer class="footer">© <?= date('Y') ?> Class Buddy Finder · Built for better campus connections</footer>
</body></html>
