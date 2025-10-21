# Bengali Font Setup Guide

## For Windows (XAMPP)

### Step 1: Install Bengali Fonts on Windows

1. Download **Noto Sans Bengali** font:
   - Visit: https://fonts.google.com/noto/specimen/Noto+Sans+Bengali
   - Click "Download family"
   - Extract the ZIP file

2. Install the fonts:
   - Right-click each `.ttf` file
   - Select "Install" or "Install for all users"
   - Common fonts to install:
     - `NotoSansBengali-Regular.ttf`
     - `NotoSansBengali-Bold.ttf`

3. Alternative: Install **SolaimanLipi** (popular Bangla font):
   - Download from: https://www.omicronlab.com/bangla-fonts.html
   - Install the `.ttf` file

### Step 2: Configure LibreOffice for Bengali

1. **Install LibreOffice** (Required for PDF conversion):
   - Download: https://www.libreoffice.org/download/download/
   - Install (choose default options)
   - Installation path is typically: `C:\Program Files\LibreOffice\`

2. **Verify LibreOffice Installation**:
   - Open Command Prompt
   - Run: `"C:\Program Files\LibreOffice\program\soffice.exe" --version`
   - You should see version information

3. **Test Bengali Font in LibreOffice**:
   - Open LibreOffice Writer
   - Type some Bengali text: `বাংলা`
   - Change font to "Noto Sans Bengali"
   - If text displays correctly, fonts are working!

### Step 3: Configure Your .docx Template

1. Open your template file in Microsoft Word
2. Select all Bengali text (if any)
3. Change font to "Noto Sans Bengali" or "SolaimanLipi"
4. For placeholders that will contain Bengali text, format them with Bengali fonts
5. Save the template

**Example template formatting:**
```
Name: {{STUDENT_NAME}}  ← Format this with Noto Sans Bengali
নাম: {{STUDENT_NAME}}   ← Bengali label with Bengali font
```

### Step 4: Test the System

1. Access your application: `http://localhost/CoverPage/public/`
2. Fill in the form with Bengali text
3. Select "Both (ZIP)" to get both DOCX and PDF
4. Open the PDF and verify Bengali text renders correctly

## For Linux Servers

```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install -y fonts-noto-bengali libreoffice-writer libreoffice-common

# CentOS/RHEL
sudo yum install -y google-noto-sans-bengali-fonts libreoffice-writer

# Verify installation
fc-list | grep -i bengali

# Test LibreOffice
libreoffice --version
```

## Troubleshooting

### PDF shows boxes/squares instead of Bengali
**Solution:** Bengali fonts are not installed system-wide
- Reinstall fonts and restart your computer
- Make sure to "Install for all users"

### LibreOffice not found error
**Solution:** LibreOffice is not installed or not in PATH
- Install LibreOffice from official website
- Or modify `generate.php` line 54 to add your LibreOffice path

### Bengali text in DOCX works, but not in PDF
**Solution:** LibreOffice can't find the fonts
- Install fonts system-wide (not just for current user)
- Restart LibreOffice service

### Slow PDF generation
**Solution:** Normal behavior on first run
- LibreOffice creates temporary profiles on first run
- Subsequent conversions will be faster

## Font Recommendations

### Best for Web & Print
- **Noto Sans Bengali** - Modern, clean, excellent Unicode support
- **Kalpurush** - Popular, readable
- **SolaimanLipi** - Traditional, widely used in Bangladesh

### For Formal Documents
- **Noto Serif Bengali** - Formal, elegant
- **Siyam Rupali** - Classic style

## Testing Bengali Input

Test your form with these sample Bengali texts:

**Student Name:** `মোহাম্মদ রহিম`
**Subject Name:** `কম্পিউটার বিজ্ঞান ও প্রযুক্তি`
**Institute:** `ঢাকা পলিটেকনিক ইনস্টিটিউট`

## UTF-8 Checklist

Ensure these files have UTF-8 encoding:
- ✅ `public/index.php` - Has `<meta charset="UTF-8">`
- ✅ `public/generate.php` - Uses `mb_substr` for Bengali
- ✅ `config/database.php` - Uses `utf8mb4` charset
- ✅ Database tables - Use `utf8mb4_unicode_ci` collation

## Need Help?

If Bengali text still doesn't work:
1. Check browser console for errors
2. Verify database charset: `SHOW CREATE TABLE templates;`
3. Test LibreOffice manually:
   ```cmd
   "C:\Program Files\LibreOffice\program\soffice.exe" --headless --convert-to pdf "test.docx"
   ```
4. Check PHP error logs in `C:\xampp\php\logs\`
