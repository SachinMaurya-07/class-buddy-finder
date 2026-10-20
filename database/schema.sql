CREATE DATABASE IF NOT EXISTS class_buddy_finder CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE class_buddy_finder;

CREATE TABLE IF NOT EXISTS students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  branch VARCHAR(40) NOT NULL,
  year VARCHAR(20) NOT NULL,
  section VARCHAR(10) NOT NULL,
  bio VARCHAR(500) NOT NULL DEFAULT '',
  hobbies VARCHAR(255) NOT NULL DEFAULT '',
  interests VARCHAR(255) NOT NULL DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS timetables (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  branch VARCHAR(40) NOT NULL,
  year VARCHAR(20) NOT NULL,
  section VARCHAR(10) NOT NULL,
  day VARCHAR(12) NOT NULL,
  period VARCHAR(20) NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  subject VARCHAR(100) NOT NULL DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_period (branch, year, section, day, period),
  INDEX idx_group_day (branch, year, section, day),
  INDEX idx_period_time (start_time, end_time)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS friend_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_id INT UNSIGNED NOT NULL,
  receiver_id INT UNSIGNED NOT NULL,
  status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_friend_pair (sender_id, receiver_id),
  CONSTRAINT fk_fr_sender FOREIGN KEY (sender_id) REFERENCES students(id) ON DELETE CASCADE,
  CONSTRAINT fk_fr_receiver FOREIGN KEY (receiver_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS meetups (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sender_id INT UNSIGNED NOT NULL,
  receiver_id INT UNSIGNED NOT NULL,
  meetup_day VARCHAR(12) NOT NULL,
  period VARCHAR(20) NOT NULL,
  message VARCHAR(300) NOT NULL DEFAULT '',
  status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_meet_sender FOREIGN KEY (sender_id) REFERENCES students(id) ON DELETE CASCADE,
  CONSTRAINT fk_meet_receiver FOREIGN KEY (receiver_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  body VARCHAR(500) NOT NULL DEFAULT '',
  url VARCHAR(255) NOT NULL DEFAULT 'notifications.php',
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_read (user_id, is_read, created_at),
  CONSTRAINT fk_notification_user FOREIGN KEY (user_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;
