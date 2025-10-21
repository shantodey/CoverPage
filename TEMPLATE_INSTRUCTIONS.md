# Template Placeholder Instructions

## How to Edit Your .docx Template

1. Open `templates/Jobe-Sheet-cover_CST.docx` in Microsoft Word
2. Replace the actual content fields with these placeholders:

### Available Placeholders:
```
{{STUDENT_NAME}}     - Student full name
{{STUDENT_ID}}       - Student ID/Index number
{{DEPARTMENT}}       - Department name (e.g., CST)
{{SEMESTER}}         - Semester (e.g., 6th)
{{SESSION}}          - Academic session (e.g., 2024-2025)
{{SUBJECT_CODE}}     - Subject code
{{SUBJECT_NAME}}     - Subject name
{{ASSIGNMENT_NO}}    - Assignment number
{{SUBMISSION_DATE}}  - Date of submission
{{TEACHER_NAME}}     - Teacher's name
{{TEACHER_DESIGNATION}} - Teacher designation
{{INSTITUTE_NAME}}   - Institute name
{{COURSE_TITLE}}     - Course title
{{ROLL_NO}}          - Roll number
{{GROUP}}            - Group (if applicable)
```

### Example:
Instead of writing:
```
Name: John Doe
ID: 123456
```

Write:
```
Name: {{STUDENT_NAME}}
ID: {{STUDENT_ID}}
```

### Important Notes:
- Use exactly `{{PLACEHOLDER}}` format (double curly braces)
- Keep the same fonts and formatting
- For Bengali template, use Noto Sans Bengali or SolaimanLipi font
- Save the file after adding placeholders

### Creating Multiple Templates:
You can create different templates for:
- Different departments (CST, Civil, Mechanical, etc.)
- Different document types (Cover page, Report, Assignment)
- English and Bengali versions

Upload them through the admin dashboard!
