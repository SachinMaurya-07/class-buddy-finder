<?php
require dirname(__DIR__).'/config.php';
unset($_SESSION['admin_id']);
redirect('login.php');
