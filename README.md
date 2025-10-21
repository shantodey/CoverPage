# 📄 Cover Page - Document Generator

A modern web application that allows visitors to generate professional document cover pages by filling out a simple form. Supports both English and Bengali languages with instant DOCX and PDF downloads.

## ✨ Features

- 🎨 **Beautiful Modern UI** - Clean, gradient-based design with smooth animations
- 📝 **Dynamic Form** - Collects all necessary information for document cover pages
- 🌐 **Bilingual Support** - English and Bengali (বাংলা) interface
- 📦 **Multiple Download Formats** - PDF, DOCX, or both in a ZIP file
- 🔒 **Secure Admin Panel** - Hidden login URL for template management
- 🚀 **No Database Storage** - Generated files are temporary and auto-deleted
- 📊 **Admin Dashboard** - Upload, manage, and track templates
- 🎯 **Department Support** - Create templates for multiple departments (CST, Civil, etc.)
- 🔍 **Activity Logging** - Track all admin actions

## 🚀 Quick Start

### Prerequisites

- **XAMPP** (Apache + MySQL + PHP 7.4+)
- **Composer** (PHP dependency manager)
- **LibreOffice** (For PDF conversion)

### Installation Steps

#### 1. Clone/Download Project
```bash
# Place the project in XAMPP htdocs folder
C:\xampp\htdocs\CoverPage\
```

#### 2. Install Dependencies
```bash
cd C:\xampp\htdocs\CoverPage
composer install
```

#### 3. Setup Database
- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Import the database schema:
  - Click "New" to create database (or use SQL tab)
  - Open file: `config/setup.sql`
  - Copy and execute the SQL

#### 4. Configure Database Connection
Edit `config/database.php` if needed (default works for XAMPP):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'coverpage_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

#### 5. Install LibreOffice (For PDF Conversion)
- Download: https://www.libreoffice.org/download/download/
- Install with default options
- Verify installation:
  ```cmd
  "C:\Program Files\LibreOffice\program\soffice.exe" --version
  ```

#### 6. Setup Bengali Fonts (Optional)
See `BENGALI_FONT_SETUP.md` for detailed instructions.

Quick steps:
- Download Noto Sans Bengali font
- Install font system-wide
- Configure your .docx template to use the font

#### 7. Prepare Your Template

**Option A:** Use the provided template
- Your template is in: `templates/Jobe-Sheet-cover_CST.docx`
- Open it in Microsoft Word
- Replace actual content with placeholders like `{{STUDENT_NAME}}`
- See `TEMPLATE_INSTRUCTIONS.md` for all available placeholders

**Option B:** Upload via admin dashboard after login

#### 8. Start the Application
- Start XAMPP (Apache + MySQL)
- Access public form: `http://localhost/CoverPage/public/`
- Access admin panel: `http://localhost/CoverPage/admin/cp-secure-entry.php`

## 🔐 Default Admin Credentials

**Important:** Change these immediately after first login!

- **URL:** `http://localhost/CoverPage/admin/cp-secure-entry.php`
- **Username:** `admin`
- **Password:** `Admin@123`

**Note:** The admin login URL is intentionally hidden and not linked from the public page.

## 📁 Project Structure

```
CoverPage/
├── admin/
│   ├── auth.php                 # Authentication logic
│   ├── cp-secure-entry.php      # Hidden admin login (SECRET URL)
│   ├── dashboard.php            # Admin dashboard
│   └── logout.php               # Logout handler
├── config/
│   ├── database.php             # Database connection
│   └── setup.sql                # Database schema
├── public/
│   ├── index.php                # Public form
│   └── generate.php             # Document generation handler
├── templates/
│   └── *.docx                   # Template files
├── tmp/                         # Temporary files (auto-cleanup)
├── vendor/                      # Composer dependencies
├── composer.json                # PHP dependencies
├── README.md                    # This file
├── TEMPLATE_INSTRUCTIONS.md     # Template placeholder guide
└── BENGALI_FONT_SETUP.md       # Bengali font setup guide
```

## 🎨 Available Placeholders for Templates

Edit your .docx template and use these placeholders:

| Placeholder | Description |
|------------|-------------|
| `{{STUDENT_NAME}}` | Student's full name |
| `{{STUDENT_ID}}` | Student ID/Index number |
| `{{ROLL_NO}}` | Roll number |
| `{{GROUP}}` | Group (e.g., A, B) |
| `{{DEPARTMENT}}` | Department name |
| `{{SEMESTER}}` | Semester (e.g., 6th) |
| `{{SESSION}}` | Academic session |
| `{{SUBJECT_CODE}}` | Subject code |
| `{{SUBJECT_NAME}}` | Subject name |
| `{{ASSIGNMENT_NO}}` | Assignment number |
| `{{SUBMISSION_DATE}}` | Submission date |
| `{{TEACHER_NAME}}` | Teacher's name |
| `{{TEACHER_DESIGNATION}}` | Teacher designation |
| `{{INSTITUTE_NAME}}` | Institute name |
| `{{COURSE_TITLE}}` | Full course title |

## 🛠️ Admin Dashboard Features

### Template Management
- ✅ Upload new .docx templates
- ✅ Activate/Deactivate templates
- ✅ Delete templates
- ✅ Download templates
- ✅ Organize by department
- ✅ Support multiple languages (EN/BN/Both)

### Statistics Dashboard
- 📊 Total templates
- 📊 Active templates
- 📊 Department count
- 📊 Recent activities

### Activity Logging
- 🔍 Track all admin actions
- 🔍 Login/logout events
- 🔍 Template uploads/deletions
- 🔍 IP address tracking

## 🎯 Usage Flow

### For Students (Public)

1. Visit: `http://localhost/CoverPage/public/`
2. Select template from dropdown
3. Choose language (English/বাংলা)
4. Fill in all required fields
5. Select download format (PDF/DOCX/Both)
6. Click "Generate Cover Page"
7. Download starts automatically
8. Files are deleted after download (no storage)

### For Admins

1. Navigate to hidden URL: `/admin/cp-secure-entry.php`
2. Login with credentials
3. View dashboard statistics
4. Upload new templates
5. Manage existing templates
6. Monitor activity logs
7. Logout when done

## 🔒 Security Features

- ✅ Hidden admin login URL (not guessable)
- ✅ Password hashing (bcrypt)
- ✅ Session-based authentication
- ✅ SQL injection protection (PDO prepared statements)
- ✅ XSS protection (input sanitization)
- ✅ File upload validation (.docx only)
- ✅ Activity logging
- ✅ No data persistence for generated files
- ✅ Temporary file cleanup

## 🐛 Troubleshooting

### Problem: "Template not found"
**Solution:**
- Check if template exists in `templates/` folder
- Verify database has correct filename
- Check file permissions

### Problem: "PDF conversion failed"
**Solution:**
- Install LibreOffice
- Verify LibreOffice path in `generate.php`
- Check if `exec()` is enabled in PHP

### Problem: Bengali text shows boxes
**Solution:**
- Install Bengali fonts system-wide
- Restart computer after font installation
- See `BENGALI_FONT_SETUP.md`

### Problem: "Database connection failed"
**Solution:**
- Start MySQL in XAMPP
- Verify database name in `config/database.php`
- Run `config/setup.sql` to create database

### Problem: Admin login not working
**Solution:**
- Verify database was setup correctly
- Default password: `Admin@123`
- Check browser cookies are enabled

### Problem: File upload fails
**Solution:**
- Check folder permissions on `templates/` folder
- Verify PHP `upload_max_filesize` setting
- Check file is actually .docx format

## 📝 Changing Admin Password

### Method 1: Via PHP
```php
<?php
// Create password hash
echo password_hash('YourNewPassword', PASSWORD_DEFAULT);
?>
```
Then update in database:
```sql
UPDATE admin_users SET password = '$2y$10$...' WHERE username = 'admin';
```

### Method 2: Via SQL directly
```sql
UPDATE admin_users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username = 'admin';
-- This sets password to: Admin@123
```

## 🚀 Production Deployment

### Important Changes for Production:

1. **Change Admin Password**
   - Update default admin password immediately

2. **Hide Error Messages**
   ```php
   ini_set('display_errors', 0);
   error_reporting(0);
   ```

3. **Enable HTTPS**
   - Get SSL certificate
   - Force HTTPS in `.htaccess`

4. **Update Database Credentials**
   - Use strong database password
   - Create dedicated database user

5. **Secure File Permissions**
   ```bash
   chmod 755 templates/
   chmod 777 tmp/
   ```

6. **Add Rate Limiting** (Optional)
   - Implement request throttling
   - Add CAPTCHA to prevent abuse

7. **Configure Backup**
   - Backup database regularly
   - Backup templates folder

## 📦 Adding New Departments

1. Login to admin dashboard
2. Click "Upload New Template"
3. Enter new department name (e.g., "Civil", "Mechanical")
4. Upload department-specific .docx template
5. Template will appear in public form dropdown

## 🎨 Customizing Colors

The UI uses a purple gradient theme. To customize:

**Admin Dashboard** (`admin/dashboard.php`):
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

**Public Form** (`public/index.php`):
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

Change the hex colors to your preferred scheme.

## 📄 License

This project is provided as-is for educational purposes.

## 🤝 Support

For issues or questions:
- Check `TEMPLATE_INSTRUCTIONS.md` for template help
- Check `BENGALI_FONT_SETUP.md` for font issues
- Review troubleshooting section above

## 📌 Quick Reference

| Item | Value |
|------|-------|
| Public URL | `http://localhost/CoverPage/public/` |
| Admin URL | `http://localhost/CoverPage/admin/cp-secure-entry.php` |
| Default Username | `admin` |
| Default Password | `Admin@123` |
| Database Name | `coverpage_db` |
| PHP Version | 7.4+ |
| Required Extensions | PDO, mbstring, zip |

---

**Made with ❤️ for students**

Generate professional cover pages in seconds! 🚀
