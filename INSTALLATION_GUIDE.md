# 📦 Cover Page - Step-by-Step Installation Guide

Follow these steps carefully to get your Cover Page application running.

## ⏱️ Estimated Time: 15-20 minutes

---

## Step 1: Verify Prerequisites ✅

### Check XAMPP Installation
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services
3. Verify they are running (green indicators)

### Check Composer Installation
Open Command Prompt and run:
```cmd
composer --version
```
If you see a version number, Composer is installed. If not, download from: https://getcomposer.org/download/

---

## Step 2: Install PHP Dependencies 📦

Open Command Prompt in the project directory:
```cmd
cd C:\xampp\htdocs\CoverPage
composer install
```

Wait for installation to complete. You should see:
```
Installing dependencies from lock file
Installing phpoffice/phpword
```

---

## Step 3: Setup Database 🗄️

### Option A: Using phpMyAdmin (Easiest)

1. Open browser and go to: `http://localhost/phpmyadmin`

2. Click **"SQL"** tab at the top

3. Open the file `C:\xampp\htdocs\CoverPage\config\setup.sql` in a text editor

4. Copy **ALL** the SQL content

5. Paste it into the SQL query box in phpMyAdmin

6. Click **"Go"** button at the bottom right

7. You should see: "✓ Database created successfully"

### Option B: Using Command Line

```cmd
cd C:\xampp\htdocs\CoverPage
mysql -u root -p < config/setup.sql
```
(Press Enter when asked for password if you don't have one)

### Verify Database Creation

1. In phpMyAdmin, click "Databases" on the left
2. You should see `coverpage_db` in the list
3. Click on it and verify these tables exist:
   - `admin_users`
   - `templates`
   - `admin_logs`

---

## Step 4: Configure Database Connection ⚙️

The default configuration should work for XAMPP. If you need to change it:

Open `config/database.php` and verify:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'coverpage_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // Leave empty for XAMPP default
```

---

## Step 5: Install LibreOffice (PDF Support) 📄

### Download LibreOffice
1. Visit: https://www.libreoffice.org/download/download/
2. Click **"Download"** for Windows
3. Run the installer
4. Choose **"Typical"** installation
5. Complete the installation

### Verify Installation
Open Command Prompt:
```cmd
"C:\Program Files\LibreOffice\program\soffice.exe" --version
```

You should see LibreOffice version information.

**Note:** If LibreOffice is installed in a different location, you'll need to update the path in `public/generate.php` at line 54.

---

## Step 6: Setup Bengali Fonts (Optional) 🔤

### Download Noto Sans Bengali
1. Visit: https://fonts.google.com/noto/specimen/Noto+Sans+Bengali
2. Click **"Download family"**
3. Extract the ZIP file

### Install Fonts
1. Open the extracted folder
2. Right-click on `NotoSansBengali-Regular.ttf`
3. Select **"Install for all users"** (important!)
4. Repeat for `NotoSansBengali-Bold.ttf`

### Restart Computer
After installing fonts, restart your computer for system-wide recognition.

**See `BENGALI_FONT_SETUP.md` for detailed Bengali setup.**

---

## Step 7: Prepare Template 📝

### Option A: Edit the Existing Template

1. Navigate to: `C:\xampp\htdocs\CoverPage\templates\`
2. Open `Jobe-Sheet-cover_CST.docx` in Microsoft Word
3. Replace actual content with placeholders (see examples below)
4. Save the file

**Example Placeholders:**
```
Student Name: {{STUDENT_NAME}}
Student ID: {{STUDENT_ID}}
Subject: {{SUBJECT_NAME}}
Department: {{DEPARTMENT}}
```

**See `TEMPLATE_INSTRUCTIONS.md` for complete placeholder list.**

### Option B: Upload Later via Admin Dashboard
You can upload templates after logging into the admin panel.

---

## Step 8: Test the Installation 🧪

### Start XAMPP Services
1. Open XAMPP Control Panel
2. Ensure **Apache** and **MySQL** are running (green)

### Access Public Form
Open browser and visit:
```
http://localhost/CoverPage/public/
```

You should see a beautiful purple gradient form!

### Access Admin Panel
Open browser and visit:
```
http://localhost/CoverPage/admin/cp-secure-entry.php
```

Login credentials:
- **Username:** `admin`
- **Password:** `Admin@123`

**⚠️ IMPORTANT: Change this password immediately after first login!**

---

## Step 9: Test Document Generation 🎉

### Generate Your First Cover Page

1. Go to: `http://localhost/CoverPage/public/`

2. Fill in the form:
   - Select a template (should show "Job Sheet Cover - CST")
   - Enter Student Name: "John Doe"
   - Enter Student ID: "123456"
   - Enter Subject Code: "CST-101"
   - Enter Subject Name: "Web Programming"
   - Fill other fields as desired

3. Select download format: **"Both (ZIP)"**

4. Click **"Generate Cover Page"**

5. Download should start automatically!

6. Open the downloaded files:
   - Check `cover_page.docx` - placeholders should be replaced
   - Check `cover_page.pdf` - should look professional

---

## ✅ Installation Complete!

Your Cover Page application is now ready to use!

## Quick Access Links

| What | URL |
|------|-----|
| **Public Form** | http://localhost/CoverPage/public/ |
| **Admin Login** | http://localhost/CoverPage/admin/cp-secure-entry.php |
| **phpMyAdmin** | http://localhost/phpmyadmin |

## Default Admin Credentials

- Username: `admin`
- Password: `Admin@123`

**🔒 Remember to change the password!**

---

## 🐛 Common Installation Issues

### "Composer not found"
**Fix:** Install Composer from https://getcomposer.org/download/

### "Database connection failed"
**Fix:**
- Make sure MySQL is running in XAMPP
- Check database credentials in `config/database.php`
- Verify database was created (check phpMyAdmin)

### "Template not found"
**Fix:**
- Run the database setup SQL again
- Check if template file exists in `templates/` folder
- Upload a new template via admin dashboard

### "PDF conversion failed"
**Fix:**
- Install LibreOffice
- Check if `exec()` is enabled in PHP
- Try downloading DOCX format instead

### Bengali text shows as boxes
**Fix:**
- Install Noto Sans Bengali fonts
- Install fonts "for all users"
- Restart computer after installation

---

## 🎓 Next Steps

1. **Customize your template** with proper placeholders
2. **Change admin password** to something secure
3. **Test with Bengali text** (if needed)
4. **Upload additional templates** for different departments
5. **Share the public URL** with students

---

## 📚 Additional Resources

- **Template Guide:** `TEMPLATE_INSTRUCTIONS.md`
- **Bengali Setup:** `BENGALI_FONT_SETUP.md`
- **Full README:** `README.md`

---

## 🎉 Congratulations!

You've successfully installed Cover Page! Students can now generate professional document covers instantly.

**Need help?** Check the troubleshooting sections in `README.md`

---

**Made with ❤️ for education**
