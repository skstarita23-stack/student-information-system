# Student Information Management System

**From Localhost to the Internet: CRUD Web Application Deployment**

## Project Description
A web application that lets authorized school staff **add, view, search, edit, and delete** student records through a browser. Built with plain HTML, CSS, JavaScript, PHP, and MySQLi, then deployed online using InfinityFree.

**Selected Scenario:** B. Student Information Management System

## Features
- Add, view, edit, and delete students (full CRUD)
- Search by Student ID, name, course, email, gender, or contact number
- Client-side (JavaScript) and server-side (PHP) validation
- Delete confirmation: "Are you sure you want to delete this student?"
- Success and error messages
- Responsive, mobile-friendly design (table becomes cards on phones)

## Technologies Used
HTML, CSS, JavaScript, PHP, MySQLi, MySQL, GitHub, InfinityFree (no frameworks)

## File Structure
| File | Purpose |
|------|---------|
| `index.php` | Dashboard: READ + search, shows the student table with Edit/Delete |
| `add.php` | CREATE: Add Student form, validates and INSERTs |
| `edit.php` | UPDATE: loads the existing record into the form, validates and UPDATEs |
| `delete.php` | DELETE: removes the selected record, then returns to the list |
| `db.php` | MySQLi connection (the only file with credentials) plus shared validation and form/page helpers |
| `style.css` | All design and responsive layout |
| `script.js` | Delete confirmation, form validation, message fade-out |
| `database.sql` | Creates the database, `students` table, and sample data |
| `README.md` | This documentation |

## Database Structure
Database: `student_information_db`, Table: `students`

| Column | Type | Notes |
|--------|------|-------|
| student_id | INT | AUTO_INCREMENT, PRIMARY KEY |
| first_name | VARCHAR(50) | NOT NULL |
| last_name | VARCHAR(50) | NOT NULL |
| gender | VARCHAR(20) | NOT NULL |
| date_of_birth | DATE | NOT NULL |
| course | VARCHAR(100) | NOT NULL |
| year_level | INT | NOT NULL |
| email | VARCHAR(100) | NOT NULL |
| contact_number | VARCHAR(30) | NOT NULL |

## CRUD Explanation
- **Create:** `add.php` validates the form, then runs `INSERT` with a prepared statement.
- **Read:** `index.php` runs `SELECT` (with optional search) and prints a table.
- **Update:** `edit.php?id=` loads the record with `SELECT`, fills the form, then runs `UPDATE ... WHERE student_id=?`.
- **Delete:** the Delete link asks for confirmation in JavaScript, then `delete.php?id=` runs `DELETE ... WHERE student_id=?`.

## Security (simple explanation)
1. **Prepared statements** (`prepare`, `bind_param`) stop SQL injection.
2. **PHP validation** of all fields, even if JavaScript is bypassed.
3. **`htmlspecialchars()`** on every displayed value stops XSS.
4. **Credentials only in `db.php`** on the server; never printed in HTML, and error messages never show them.
5. **JavaScript confirmation** before deleting.

## Localhost Installation (XAMPP)
1. Install XAMPP, open the Control Panel, **Start Apache** and **MySQL**.
2. Copy the `student-information-system` folder into `C:\xampp\htdocs\`.
3. Go to `http://localhost/phpmyadmin` → **Import** tab → choose `database.sql` → **Go**. (Or paste the file's contents in the **SQL** tab.)
4. Edit `db.php`:
   ```php
   $servername = "localhost";
   $username   = "root";
   $password   = "";
   $dbname     = "student_information_db";
   ```
5. Open `http://localhost/student-information-system/`.

## GitHub Instructions
> **Never push your real InfinityFree password.** Keep `db.php` on GitHub with the placeholders (`YOUR_DATABASE_PASSWORD`, etc.). Put real credentials in `db.php` only on the InfinityFree server.

1. Create a free account at github.com → **New repository** → name: `student-information-system` → Public → **Create**.
2. Easiest way: on the new repo page click **uploading an existing file**, drag all files from the project folder, then **Commit changes**.
3. Or with Git:
   ```
   cd student-information-system
   git init
   git add .
   git commit -m "Student Information Management System"
   git branch -M main
   git remote add origin https://github.com/YOUR_USERNAME/student-information-system.git
   git push -u origin main
   ```
4. Open the repo and check `db.php` still shows placeholders.

## InfinityFree Deployment
1. **Create an account:** go to infinityfree.com → **Sign Up** → verify your email.
2. **Create a hosting account:** Dashboard → **Create Account** → choose a free subdomain (e.g. `mystudents.infinityfreeapp.com`) → pick a password → **Create**. Wait until it says Active.
3. **Create the MySQL database:** open the **Control Panel** (cPanel) → **MySQL Databases** → enter a name (e.g. `student_information_db`) → **Create Database**. InfinityFree adds a prefix, so the real name looks like `if0_12345678_student_information_db`.
4. **Find the credentials** (MySQL Databases page or **Account Details**):
   - **MySQL Hostname:** e.g. `sql123.infinityfree.com` (NOT `localhost`)
   - **MySQL Username:** e.g. `if0_12345678`
   - **Database Name:** the full name with prefix
   - **Password:** your hosting account (vPanel) password
5. **Import the table:** Control Panel → **phpMyAdmin** → **Connect Now** → click your database in the left list → **Import** → choose `database.sql`.
   > The file starts with `CREATE DATABASE` / `USE student_information_db`, which InfinityFree does not allow. Before importing, delete the first two lines (`CREATE DATABASE...` and `USE...`), or paste only the `CREATE TABLE` and `INSERT` parts in the **SQL** tab.
6. **Edit `db.php`** (the only values you replace):
   ```php
   $servername = "sqlXXX.infinityfree.com";      // your MySQL Hostname
   $username   = "if0_XXXXXXXX";                // your MySQL Username
   $password   = "your_account_password";        // your password
   $dbname     = "if0_XXXXXXXX_student_information_db"; // full database name
   ```
7. **Upload the files:** Control Panel → **Online File Manager** (or FTP with FileZilla) → open the **`htdocs`** folder → delete the default `index2.html` → upload `index.php`, `add.php`, `edit.php`, `delete.php`, `db.php`, `style.css`, `script.js` directly into `htdocs`.
8. **Get the public URL:** your subdomain, e.g. `http://mystudents.infinityfreeapp.com`. Open it in a browser. (New accounts can take a few minutes to activate.)

**Troubleshooting:** "Database connection failed" → recheck the 4 values in `db.php` (hostname is not `localhost`; database name includes the prefix). Blank table → the SQL import did not finish.

## Online Testing Instructions
Use the public URL for all tests:
1. **Create:** Add Student → fill the form → Save → success message, new row in the list; confirm in phpMyAdmin (Browse).
2. **Read:** open the dashboard; records match phpMyAdmin; try searching.
3. **Update:** click Edit → form is pre-filled → change a field → Update → change appears.
4. **Delete:** click Delete → confirm → success message → row gone (also gone in phpMyAdmin).
5. **Public access:** open the URL on another browser or phone and perform an Add.

## 30-Point Rubric Checklist
| Criteria | Pts | How it is satisfied |
|---|---|---|
| Create | 5 | `add.php` form, JS + PHP validation, prepared INSERT, success message, redirect to list |
| Read | 5 | `index.php` table of all required columns from MySQL, Actions column, search box |
| Update | 4 | `edit.php` pre-filled form, validation, prepared UPDATE on correct ID, success message |
| Delete | 4 | JS confirmation text, `delete.php` prepared DELETE, redirect + success message |
| MySQLi Integration | 4 | `db.php` shared by all pages, MySQLi, error handling, prepared statements |
| Online Deployment | 4 | Hosted on InfinityFree with a public URL and online MySQL database |
| Online Testing | 2 | All four CRUD tests done on the public URL, plus another device |
| GitHub Repository | 2 | Source code and professional README, no real passwords |
| **Total** | **30** | |

## Screenshots to Take
1. GitHub repository (files + README visible)
2. phpMyAdmin `students` table structure and data
3. Student list (public URL visible in the address bar)
4. Add Student form
5. "Student added successfully!" message
6. Edit Student form pre-filled
7. "Student updated successfully!" message
8. Delete confirmation popup
9. "Student deleted successfully!" message
10. Public InfinityFree site (full URL visible)
11. Online CRUD proof: phpMyAdmin before/after each operation
