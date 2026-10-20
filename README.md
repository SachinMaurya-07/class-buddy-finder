# Class Buddy Finder (PHP + MySQL)

A responsive student web app that helps classmates find shared free periods.

## Features
- Student registration and login
- Password hashing with `password_hash()` / `password_verify()`
- Student profile with bio, hobbies and interests
- Admin login and dashboard
- Admin-only timetable management via CSV upload (students cannot edit timetable data)
- Buddy finder based on matching branch, year, section, day and free period
- Friend requests (send / accept / reject)
- Meet-up requests (send / accept / reject)
- Notifications
- Responsive dark-blue UI

## Requirements
- PHP 8.0+ (PDO MySQL extension enabled)
- MySQL 5.7+ or MariaDB
- Apache/Nginx or XAMPP/MAMP/Hostinger PHP hosting

## Install
1. Create a MySQL database, e.g. `class_buddy_finder`.
2. Import `database/schema.sql` using phpMyAdmin or MySQL CLI.
3. Copy `config.example.php` to `config.php` and edit it with your database credentials and site URL. `config.php` is intentionally excluded from Git.
4. Upload the project files to your PHP web root.
5. Visit `setup_admin.php` once to create the first admin account. **Delete this file from the server immediately after setup.**
6. Register a student and sign in.
7. Sign in as admin using `/admin/login.php`.
8. Upload timetable CSV from the admin dashboard.

## CSV format
The first row must be the header below:

`branch,year,section,day,period,start_time,end_time,subject`

Example:
`CSE,2,A,Monday,1,09:00,09:50,DBMS`
`CSE,2,A,Monday,2,09:50,10:40,FREE`

Use `FREE` as the subject for a free period. Branch/year/section values must match student registration values. Day values: Monday, Tuesday, Wednesday, Thursday, Friday, Saturday.

## Security notes
- Use HTTPS in production.
- Change `APP_URL` and DB credentials.
- Delete `setup_admin.php` after creating an admin.
- Keep `uploads/` non-executable; uploaded CSVs are parsed in memory and not saved.
- For production, add email verification, CSRF protection, rate limiting and password-reset flow.

## Folder map
- `index.php` - landing page
- `register.php`, `login.php`, `logout.php` - student auth
- `dashboard.php` - student home
- `edit_profile.php` - profile settings
- `find_buddy.php` - shared free-period matches
- `friends.php` - friend requests and list
- `meetups.php` - meet-up requests
- `notifications.php` - notifications
- `admin/` - admin pages and CSV timetable upload
- `database/schema.sql` - database schema

## GitHub publishing checklist

- `config.php` is intentionally not included; create it from `config.example.php` on your own machine/server.
- Never commit real database credentials, student data, production URLs, or exports.
- `data/*.json` and local runtime files are ignored by Git.
- The included `.htaccess` is for Apache. Configure equivalent access restrictions if deploying on Nginx.
- Delete `setup_admin.php` from the live server immediately after creating the first admin account.
