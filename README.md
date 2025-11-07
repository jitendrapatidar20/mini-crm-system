# 🚀 Mini CRM  System (Laravel 11)

A modern **Laravel 11** based Mini CRM system featuring **dynamic settings, auditing, security, and login tracking** — built for scalability and maintainability.


## 🧩 Overview

This system includes:


✅ **Secure Login & Logout System**  
✅ **CRUD for Contacts (AJAX-powered)**  
✅ **Custom Dynamic Fields**  
✅ **Profile & File Uploads**  
✅ **Filtering by Name, Email, Gender**  
✅ **Contact Merge System with Data Integrity**  
✅ **Dynamic Settings Management**  
✅ **Audit Logging (Create/Update/Delete)**  
✅ **Login History Tracking (IP, Browser, Location)**  
✅ **IP Blocking After Multiple Failed Logins**  
✅ **Responsive UI (Bootstrap 5 + DataTables)**  
✅ **Seeder with Demo Data (Settings, Page Titles, Admin)**  



## ⚙️ How to setup 

Clone repo
copy .env.example -> .env, set DB settings (sqlite: database/database.sqlite or mysql)
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve


## 🧑‍💼 Default Admin Credentials

Email : admin@gmail.com	
Password : 	admin

---

## 🔒 Advanced Admin Features

### 📨 Configurable SMTP  
Easily configure SMTP credentials via `.env` or dynamic **project settings** table for runtime updates.

---

## 🧾 Dynamic Slug Generation
- Automatically generates **unique  slugs** from contact,PageTitle for clean URLs.  
- Slugs auto-update if the title changes.

---

## 🚫 IP Blocking After 4 Failed Login Attempts
- Prevents brute-force attacks using Laravel’s built-in `throttle:4,5` middleware.  
- Locks login for **5 minutes** after **4 consecutive failures** from the same IP.

---

## 🌍 Login History Management
- Tracks every login attempt with:
  - User ID  
  - IP Address  
  - Browser info  
  - Latitude & Longitude  
  - Timestamp  
- Data is stored in `login_histories` table.  
- Automatically updates `users.last_login_ip` and `users.last_login_at`.

---

## 🧠 Audit Management (System-wide Logging)
- Custom **Auditable trait** records `create`, `update`, and `delete` events across models.  
- Stores **old and new data** in JSON format within an `audits` table.  
- Captures the **user ID** responsible for each change.

---

## ⚙️ Dynamic Project Settings
- Configurable via database (`project_settings` or `settings` table).  
- Stores key constants like:
  - Project Name  
  - Support Email  
  - Footer Text  
  - Social Links  

### 🔁 Auto-Caching
- Cached automatically via `ProjectSetting` model.

### 💡 Global Access
You can access settings anywhere in Laravel using:
```php
config('project.project_name');
ProjectSetting::getValue('footer_text');

🧰 Tech Stack
Laravel 11
PHP 8.2+
MySQL 8
Bootstrap 5
Eloquent ORM
cviebrock/eloquent-sluggable (for automatic slugs)
jQuery & DataTables (for admin UI)


⚙️ Installation Guide
1️⃣ Clone Repository
git clone https://github.com/jitendrapatidar20/mini-crm-system.git
cd min-crm-system


4️⃣ Database Setup
php artisan migrate --seed


💡 The seeder automatically populates:
Admin Details (AdminSeeder)
Contact Details (ContactSeeder)
CustomField Details (CustomFieldSeeder)
Project settings (SettingsTableSeeder)
Page titles (PageTitlesTableSeeder)


🧑‍💼 Admin Panel Routes Overview
Section	URL	Description
Dashboard	/dashboard	Main Admin Dashboard
Contacts	/admin/contacts	Manage contacts
Settings	/admin/settings	Manage dynamic project settings
Page Titles	/admin/pages	Manage page SEO titles
Logout	/logout	Secure admin logout


🧪 Testing
Run Laravel feature and unit tests:
php artisan test