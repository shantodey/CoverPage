# ⚡ Quick Start Guide - Cover Page

## 🚀 1-Minute Setup

### If you have XAMPP + Composer installed:

```cmd
# 1. Install dependencies
cd C:\xampp\htdocs\CoverPage
composer install

# 2. Setup database
Open http://localhost/phpmyadmin
Run the SQL from: config/setup.sql

# 3. Done! Access the app:
Public: http://localhost/CoverPage/public/
Admin:  http://localhost/CoverPage/admin/cp-secure-entry.php
```

---

## 📋 Quick Reference Card

### Public Access
- **URL:** `http://localhost/CoverPage/public/`
- **Purpose:** Students fill form and download documents
- **No login required**

### Admin Access
- **URL:** `http://localhost/CoverPage/admin/cp-secure-entry.php`
- **Username:** `admin`
- **Password:** `Admin@123` ⚠️ CHANGE THIS!
- **Features:** Upload templates, manage departments, view logs

---

## 📁 Where Everything Is

| What | Location |
|------|----------|
| Public form | `public/index.php` |
| Document generator | `public/generate.php` |
| Admin login | `admin/cp-secure-entry.php` |
| Admin dashboard | `admin/dashboard.php` |
| Templates (upload here) | `templates/` |
| Database config | `config/database.php` |
| Database setup SQL | `config/setup.sql` |

---

## 🎨 Template Placeholders Cheat Sheet

Just open your `.docx` file and replace content with these:

```
{{STUDENT_NAME}}         → Student's name
{{STUDENT_ID}}           → Student ID
{{ROLL_NO}}              → Roll number
{{DEPARTMENT}}           → Department (CST, Civil, etc.)
{{SEMESTER}}             → Semester
{{SESSION}}              → Academic session
{{SUBJECT_CODE}}         → Subject code
{{SUBJECT_NAME}}         → Subject name
{{ASSIGNMENT_NO}}        → Assignment number
{{SUBMISSION_DATE}}      → Date
{{TEACHER_NAME}}         → Teacher name
{{TEACHER_DESIGNATION}}  → Teacher designation
{{INSTITUTE_NAME}}       → Institute name
{{COURSE_TITLE}}         → Course title
{{GROUP}}                → Group
```

---

## ✅ Pre-Launch Checklist

Before sharing with students:

- [ ] XAMPP Apache and MySQL running
- [ ] Database created (`coverpage_db` exists)
- [ ] Composer dependencies installed (`vendor/` folder exists)
- [ ] Template uploaded and active (check admin dashboard)
- [ ] Admin password changed from default
- [ ] Test document generation (fill form + download)
- [ ] PDF generation works (LibreOffice installed)
- [ ] Bengali fonts installed (if using Bangla)

---

## 🐛 Quick Troubleshooting

| Problem | Quick Fix |
|---------|-----------|
| White screen | Check if Apache/MySQL running in XAMPP |
| Database error | Run `config/setup.sql` in phpMyAdmin |
| Template not found | Upload template via admin dashboard |
| PDF fails | Install LibreOffice OR use DOCX format |
| Composer error | Run `composer install` in project folder |
| Admin can't login | Verify database setup, check password |

---

## 🎯 Common Tasks

### Upload New Template
1. Login to admin
2. Scroll to "Upload New Template"
3. Fill name, department, language
4. Upload `.docx` file
5. Done!

### Add New Department
1. Login to admin
2. Upload template with new department name
3. It auto-creates the department
4. Will show in public form dropdown

### Change Admin Password
```sql
-- In phpMyAdmin, run:
UPDATE admin_users
SET password = '$2y$10$NEW_HASH_HERE'
WHERE username = 'admin';
```
Generate hash: Create a PHP file with:
```php
<?php echo password_hash('NewPassword123', PASSWORD_DEFAULT); ?>
```

### Make Template Inactive
1. Login to admin dashboard
2. Find template in list
3. Click "Deactivate" button
4. Won't show in public form anymore

---

## 💡 Pro Tips

- **Multiple templates:** Upload different templates for different purposes
- **Department organization:** Name templates clearly (e.g., "Assignment Cover - CST")
- **Bengali support:** Use Noto Sans Bengali font in Word template
- **Template backup:** Download templates from admin before making changes
- **Activity logs:** Check admin logs to see who uploaded/deleted what

---

## 📱 Sharing with Students

Share this URL with students:
```
http://localhost/CoverPage/public/
```

**For production server, replace `localhost` with your domain:**
```
http://yourschool.edu/CoverPage/public/
```

---

## 🔒 Security Note

The admin URL is **intentionally hidden**:
```
/admin/cp-secure-entry.php
```

**Do not share this publicly!** Only you need to know this URL.

---

## 📚 Need More Help?

- **Full installation:** See `INSTALLATION_GUIDE.md`
- **Template details:** See `TEMPLATE_INSTRUCTIONS.md`
- **Bengali setup:** See `BENGALI_FONT_SETUP.md`
- **Complete docs:** See `README.md`

---

**That's it! You're ready to generate cover pages! 🎉**
