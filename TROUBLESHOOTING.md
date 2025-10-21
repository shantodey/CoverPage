# 🔧 Troubleshooting Guide - Cover Page

## ✅ ISSUE RESOLVED: Internal Server Error

**Problem:** "Internal Server Error" when accessing the application

**Cause:** The original `.htaccess` file contained `<DirectoryMatch>` and `<Directory>` directives that are not allowed in `.htaccess` files. These directives can only be used in the main Apache configuration file.

**Solution:** ✅ FIXED - The `.htaccess` file has been updated to remove incompatible directives.

---

## 🚀 Quick Status Check

Run these commands to verify everything is working:

```bash
# Test main page (should return 302 - redirect)
curl -s -o /dev/null -w "%{http_code}" "http://localhost/CoverPage/"

# Test public page (should return 200)
curl -s -o /dev/null -w "%{http_code}" "http://localhost/CoverPage/public/"

# Test admin page (should return 200)
curl -s -o /dev/null -w "%{http_code}" "http://localhost/CoverPage/admin/cp-secure-entry.php"
```

**Expected Results:**
- Main page: `302` (redirects to public)
- Public page: `200` (OK)
- Admin page: `200` (OK)

---

## 📋 Common Issues & Solutions

### 1. Internal Server Error (500)

**Symptoms:** White page with "Internal Server Error"

**Check:**
```bash
# View Apache error log
tail -20 "C:\xampp\apache\logs\error.log"
```

**Common Causes:**
- `.htaccess` syntax error → Check error log for specific line
- PHP syntax error → Check PHP error log
- Missing PHP modules → Verify PHP extensions loaded
- File permissions → Ensure files are readable

**Solution:**
- If `.htaccess` error: Comment out problematic lines
- If PHP error: Check syntax in the PHP file mentioned in log
- Restart Apache after any changes

---

### 2. Database Connection Failed

**Symptoms:** Error message about database connection

**Check:**
```bash
# Verify MySQL is running
# Open XAMPP Control Panel
# Check if MySQL has green "Running" status
```

**Solution:**
1. Start MySQL in XAMPP Control Panel
2. Verify database exists:
   - Go to http://localhost/phpmyadmin
   - Check if `coverpage_db` exists
   - If not, run `config/setup.sql`

3. Verify credentials in `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'coverpage_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Empty for default XAMPP
   ```

---

### 3. Template Not Found

**Symptoms:** Error when trying to generate document

**Check:**
```bash
# List templates in database
# Go to phpMyAdmin → coverpage_db → templates table
# Verify records exist and is_active = 1
```

**Solution:**
1. Login to admin panel
2. Upload a template:
   - Click "Upload New Template"
   - Fill in details
   - Upload `.docx` file
3. Verify template appears in list
4. Ensure template is "Active" (green badge)

---

### 4. PDF Conversion Failed

**Symptoms:** Error during PDF generation, only DOCX downloads

**Check:**
```bash
# Test if LibreOffice is installed
"C:\Program Files\LibreOffice\program\soffice.exe" --version
```

**Solution:**
1. Install LibreOffice from https://www.libreoffice.org/download/
2. Restart Apache after installation
3. Test again

**Alternative:**
- Use "DOCX Only" download format
- PDF conversion is optional

---

### 5. Blank Page (No Error)

**Symptoms:** White blank page, no error message

**Check:**
```bash
# Check if PHP is processing files
# View page source in browser (Ctrl+U)
# If you see PHP code, PHP is not running
```

**Solution:**
1. Verify Apache is running in XAMPP
2. Ensure file has `.php` extension
3. Check if PHP module is loaded:
   - XAMPP Control Panel → Apache → Config → httpd.conf
   - Look for: `LoadModule php_module ...`
4. Restart Apache

---

### 6. Composer Install Fails

**Symptoms:** Error when running `composer install`

**Check:**
```bash
# Verify Composer is installed
composer --version
```

**Solution:**
1. Install Composer from https://getcomposer.org/download/
2. Add Composer to Windows PATH
3. Run from project directory:
   ```bash
   cd C:\xampp\htdocs\CoverPage
   composer install
   ```

---

### 7. Admin Can't Login

**Symptoms:** "Invalid credentials" error

**Check:**
```bash
# Verify admin_users table exists and has data
# Go to phpMyAdmin → coverpage_db → admin_users
```

**Solution:**
1. Verify database was setup: Run `config/setup.sql`
2. Use default credentials:
   - Username: `admin`
   - Password: `Admin@123`
3. Clear browser cookies and try again
4. If still failing, reset password:
   ```sql
   UPDATE admin_users
   SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
   WHERE username = 'admin';
   ```

---

### 8. File Upload Fails

**Symptoms:** Error when uploading template in admin

**Check:**
```bash
# Check templates folder exists and is writable
ls -la C:\xampp\htdocs\CoverPage\templates\
```

**Solution:**
1. Create templates folder if missing:
   ```bash
   mkdir C:\xampp\htdocs\CoverPage\templates
   ```
2. Check PHP upload settings in `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
3. Restart Apache after changing `php.ini`

---

### 9. Bengali Text Shows Boxes

**Symptoms:** Bengali characters display as squares/boxes

**Check:**
- Open generated PDF/DOCX
- Look for □□□ instead of বাংলা

**Solution:**
1. Install Noto Sans Bengali font:
   - Download from https://fonts.google.com/noto/specimen/Noto+Sans+Bengali
   - Install "for all users"
2. Restart computer
3. Open Word template, select Bengali text
4. Change font to "Noto Sans Bengali"
5. Save template
6. Re-upload to admin panel

**See:** `BENGALI_FONT_SETUP.md` for detailed instructions

---

### 10. Slow PDF Generation

**Symptoms:** Long wait time (30+ seconds) for PDF

**Explanation:** Normal on first run
- LibreOffice creates temporary profile on first use
- Subsequent conversions will be faster (5-10 seconds)

**Solution:**
- Wait for first generation to complete
- Next attempts will be faster
- If always slow, check server resources

---

## 🔍 Debugging Tools

### View Apache Error Log
```bash
tail -f C:\xampp\apache\logs\error.log
```

### View PHP Errors in Browser
Edit `config/database.php` temporarily:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
**⚠️ Remove this in production!**

### Test Database Connection
Create `test_db.php`:
```php
<?php
require_once 'config/database.php';
try {
    $db = Database::getInstance();
    echo "✅ Database connected successfully!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```
Access: `http://localhost/CoverPage/test_db.php`
Delete after testing!

### Test Template Processing
Check if PHPWord is loaded:
```php
<?php
require_once 'vendor/autoload.php';
echo "✅ PHPWord loaded successfully!";
var_dump(class_exists('PhpOffice\PhpWord\TemplateProcessor'));
?>
```

---

## 🚨 Emergency Quick Fixes

### Complete Reset (Nuclear Option)

If nothing works:

1. **Backup your templates folder**
   ```bash
   cp -r templates templates_backup
   ```

2. **Drop and recreate database**
   - Go to phpMyAdmin
   - Drop `coverpage_db`
   - Run `config/setup.sql` again

3. **Reinstall dependencies**
   ```bash
   rm -rf vendor
   composer install
   ```

4. **Clear browser cache**
   - Ctrl+Shift+Delete
   - Clear all cookies and cache

5. **Restart XAMPP**
   - Stop Apache and MySQL
   - Close XAMPP
   - Restart XAMPP
   - Start Apache and MySQL

---

## 📊 Health Check Checklist

Run through this checklist:

- [ ] XAMPP Apache: Running (green)
- [ ] XAMPP MySQL: Running (green)
- [ ] Database `coverpage_db` exists
- [ ] Tables created (admin_users, templates, admin_logs)
- [ ] `vendor/` folder exists (Composer installed)
- [ ] `templates/` folder exists and writable
- [ ] At least one template in database with is_active=1
- [ ] Can access http://localhost/CoverPage/public/
- [ ] Can login to admin panel
- [ ] Can generate DOCX file
- [ ] LibreOffice installed (for PDF)

---

## 🆘 Still Having Issues?

### Check System Requirements
- ✅ Windows 7/10/11
- ✅ XAMPP with PHP 7.4+ or 8.x
- ✅ MySQL 5.7+ or MariaDB
- ✅ At least 256MB PHP memory
- ✅ Composer installed
- ✅ LibreOffice (optional, for PDF)

### Review Documentation
1. `INSTALLATION_GUIDE.md` - Setup steps
2. `SETUP_CHECKLIST.txt` - Step-by-step checklist
3. `README.md` - Complete documentation

### Common Mistake Checklist
- [ ] Did you run `composer install`?
- [ ] Did you import `config/setup.sql`?
- [ ] Is MySQL running in XAMPP?
- [ ] Are you using correct admin password (`Admin@123`)?
- [ ] Did you create `templates` folder?
- [ ] Is at least one template uploaded?

---

## 🔐 Security Note

When troubleshooting, you might enable error display. **Remember to disable it** when done:

```php
// In production, always use:
ini_set('display_errors', 0);
error_reporting(0);
```

---

## 📝 Reporting Issues

If you find a bug or issue:

1. **Check error logs** (Apache and PHP)
2. **Note exact error message**
3. **List steps to reproduce**
4. **Check if database is setup correctly**
5. **Verify all files are in place**

---

## ✅ System Working Correctly?

You'll know everything is working when:

1. ✅ Public page loads with purple gradient
2. ✅ Template appears in dropdown
3. ✅ Form submits without errors
4. ✅ DOCX file downloads
5. ✅ Placeholders are replaced in document
6. ✅ PDF generates (if LibreOffice installed)
7. ✅ Admin panel accessible and functional

---

**Your system should now be working perfectly!** 🎉

**If you still encounter issues, review the logs and this guide.**

---

**Last Updated:** After fixing `.htaccess` DirectoryMatch issue
**Status:** System operational ✅
