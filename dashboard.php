<?php
require __DIR__ . '/config.php'; student_required();
$me = current_student($pdo);
$stmt = $pdo->prepare("SELECT COUNT(*) FROM friend_requests WHERE receiver_id=? AND status='pending'");
$stmt->execute([$me['id']]); $pendingFriends = (int)$stmt->fetchColumn();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM meetups WHERE receiver_id=? AND status='pending'");
$stmt->execute([$me['id']]); $pendingMeetups = (int)$stmt->fetchColumn();
$stmt = $pdo->prepare("SELECT day,period,start_time,end_time,subject FROM timetables WHERE branch=? AND year=? AND section=? ORDER BY FIELD(day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time");
$stmt->execute([$me['branch'],$me['year'],$me['section']]); $timetable = $stmt->fetchAll();
$pageTitle = 'Dashboard'; require __DIR__ . '/includes/header.php';
?>
<div class="section-head"><div><span class="pill">STUDENT DASHBOARD</span><h2 style="margin-top:9px">Hey, <?= e($me['name']) ?> 👋</h2><p><?= e($me['branch']) ?> · Year <?= e($me['year']) ?> · Section <?= e($me['section']) ?></p></div><a class="btn btn-primary" href="find_buddy.php">Find a buddy →</a></div>
<div class="stats-grid"><div class="stat"><strong><?= count($timetable) ?></strong><span>Timetable periods loaded</span></div><div class="stat"><strong><?= $pendingFriends ?></strong><span>Pending friend requests</span></div><div class="stat"><strong><?= $pendingMeetups ?></strong><span>Meet-up requests</span></div></div>
<div class="panel"><div class="between"><div><h3>Your class timetable</h3><p class="subtle">Timetable is maintained by your administrator.</p></div><span class="pill"><?= count($timetable) ? 'LOADED' : 'NOT UPLOADED' ?></span></div>
<?php if (!$timetable): ?><p class="muted">No timetable has been uploaded for your branch, year and section yet. Please contact your administrator.</p>
<?php else: ?><div class="table-wrap"><table class="data-table"><thead><tr><th>Day</th><th>Period</th><th>Time</th><th>Subject / Status</th></tr></thead><tbody><?php foreach($timetable as $t): ?><tr><td><?= e($t['day']) ?></td><td><?= e($t['period']) ?></td><td><?= e(substr($t['start_time'],0,5)) ?>–<?= e(substr($t['end_time'],0,5)) ?></td><td><?= strtoupper(trim($t['subject']))==='FREE' || trim($t['subject'])==='' ? '<span class="pill">FREE</span>' : e($t['subject']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</div>
<div class="feature-grid">
<a class="feature" href="find_buddy.php"><div class="feature-icon">🤝</div><h3>Find buddies</h3><p>Find classmates with the same free period.</p></a>
<a class="feature" href="friends.php"><div class="feature-icon">💙</div><h3>Friends</h3><p>Manage your connections and requests.</p></a>
<a class="feature" href="meetups.php"><div class="feature-icon">☕</div><h3>Meet-up plans</h3><p>Plan a study session or campus break.</p></a>
<a class="feature" href="edit_profile.php"><div class="feature-icon">👤</div><h3>Your profile</h3><p>Update your bio, hobbies and interests.</p></a>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
