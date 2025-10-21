<?php
/**
 * Public Form - Cover Page Generator (English Only)
 */

require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance();

// Fetch active templates
$templates = $db->fetchAll("SELECT * FROM templates WHERE is_active = 1 ORDER BY department, name");
$departments = array_unique(array_column($templates, 'department'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cover Page Generator</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
               * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 12px;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 18px;
            opacity: 0.95;
            font-weight: 400;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h2 {
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .download-options {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .download-option {
            flex: 1;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .download-option:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .download-option input[type="radio"] {
            display: none;
        }

        .download-option.selected {
            border-color: #667eea;
            background: #eef2ff;
        }

        .download-option label {
            cursor: pointer;
            display: block;
        }

        .download-option .icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .download-option .title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .download-option .desc {
            font-size: 12px;
            color: #64748b;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .info-box {
            margin-top: 24px;
            padding: 16px;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            font-size: 13px;
            color: #0c4a6e;
        }

        .info-box strong {
            display: block;
            margin-bottom: 4px;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e5e7eb;
            border-top-color: #667eea;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fecaca;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 36px;
            }

            .form-card {
                padding: 30px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .download-options {
                flex-direction: column;
            }
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 30px;
            opacity: 0.9;
            font-size: 14px;
        }
        </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Cover Page</h1>
            <p>Generate professional document covers instantly</p>
        </div>

        <div class="form-card">
            <div class="form-header">
                <h2>Create Your Document</h2>
                <p>Fill in the details below to generate your custom cover page</p>
            </div>

            <div id="errorMessage" class="error-message"></div>

            <form id="coverForm" method="POST" action="generate.php">
                <!-- Template Selection -->
                <div class="form-group">
                    <label for="template_id">Select Template <span class="required">*</span></label>
                    <select id="template_id" name="template_id" required>
                        <option value="">Choose a template...</option>
                        <?php foreach ($departments as $dept): ?>
                            <optgroup label="<?php echo htmlspecialchars($dept); ?>">
                                <?php foreach ($templates as $template): ?>
                                    <?php if ($template['department'] === $dept): ?>
                                        <option value="<?php echo $template['id']; ?>">
                                            <?php echo htmlspecialchars($template['name']); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Student Information -->
                <div class="form-group">
                    <label for="student_name">Student Name <span class="required">*</span></label>
                    <input type="text" id="student_name" name="student_name" required placeholder="Enter full name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_index">Index No. <span class="required">*</span></label>
                        <input type="text" id="student_index" name="student_index" required placeholder="e.g., CST-M-1914">
                    </div>

                    <div class="form-group">
                        <label for="board_roll">Board Roll <span class="required">*</span></label>
                        <input type="text" id="board_roll" name="board_roll" required placeholder="e.g., 759291">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="semester">Semester <span class="required">*</span></label>
                        <input type="text" id="semester" name="semester" required placeholder="e.g., 6th">
                    </div>

                    <div class="form-group">
                        <label for="batch">Batch <span class="required">*</span></label>
                        <input type="text" id="batch" name="batch" required placeholder="e.g., 2024-2025">
                    </div>
                </div>

                <!-- Subject Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="subject_code">Subject Code <span class="required">*</span></label>
                        <input type="text" id="subject_code" name="subject_code" required placeholder="e.g., 67153">
                    </div>

                    <div class="form-group">
                        <label for="subject_name">Subject Name <span class="required">*</span></label>
                        <input type="text" id="subject_name" name="subject_name" required placeholder="Enter subject name">
                    </div>
                </div>

                <!-- Experiment Information -->
                <div class="form-group">
                    <label for="experiment_name">Name of Experiment <span class="required">*</span></label>
                    <input type="text" id="experiment_name" name="experiment_name" required placeholder="Enter experiment name">
                </div>

                <!-- Assignment Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="assignment_no">Assignment No. <span class="required">*</span></label>
                        <input type="text" id="assignment_no" name="assignment_no" required placeholder="e.g., 01">
                    </div>

                    <div class="form-group">
                        <label for="session">Session <span class="required">*</span></label>
                        <input type="text" id="session" name="session" required placeholder="e.g., 2024-2025">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_expt">Date of Expt. <span class="required">*</span></label>
                        <input type="date" id="date_of_expt" name="date_of_expt" required>
                    </div>

                    <div class="form-group">
                        <label for="submission_date">Submission Date <span class="required">*</span></label>
                        <input type="date" id="submission_date" name="submission_date" required>
                    </div>
                </div>

                <!-- Teacher Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="teacher_name">Teacher Name <span class="required">*</span></label>
                        <input type="text" id="teacher_name" name="teacher_name" required placeholder="Teacher's name">
                    </div>

                    <div class="form-group">
                        <label for="teacher_designation">Teacher Designation <span class="required">*</span></label>
                        <input type="text" id="teacher_designation" name="teacher_designation" required placeholder="e.g., Lecturer">
                    </div>
                </div>

                <!-- Download Options -->
                <div class="form-group">
                    <label>Download Format <span class="required">*</span></label>
                    <div class="download-options">
                        <div class="download-option" onclick="selectDownloadType('pdf', this)">
                            <input type="radio" name="download_type" value="pdf" id="type_pdf">
                            <label for="type_pdf">
                                <div class="icon">📕</div>
                                <div class="title">PDF Only</div>
                                <div class="desc">Requires LibreOffice</div>
                            </label>
                        </div>

                        <div class="download-option selected" onclick="selectDownloadType('docx', this)">
                            <input type="radio" name="download_type" value="docx" id="type_docx" checked>
                            <label for="type_docx">
                                <div class="icon">📘</div>
                                <div class="title">DOCX Only</div>
                                <div class="desc">Recommended</div>
                            </label>
                        </div>

                        <div class="download-option" onclick="selectDownloadType('both', this)">
                            <input type="radio" name="download_type" value="both" id="type_both">
                            <label for="type_both">
                                <div class="icon">📦</div>
                                <div class="title">Both (ZIP)</div>
                                <div class="desc">Requires LibreOffice</div>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    🚀 Generate Cover Page
                </button>

                <div class="info-box">
                    <strong>ℹ️ Important Note:</strong>
                    Your generated files are temporary and will be automatically deleted after download. No data is stored on our servers.
                </div>
            </form>
        </div>

        <div class="footer">
            <p>Made with ❤️ for students</p>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3 style="margin-bottom: 8px; color: #1e293b;">Generating your document...</h3>
            <p style="color: #64748b; font-size: 14px;">This may take a few seconds</p>
        </div>
    </div>

    <script>
        // Download type selection
        function selectDownloadType(type, element) {
            document.querySelectorAll('.download-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('type_' + type).checked = true;
        }

        // Form submission
        document.getElementById('coverForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });

            if (!isValid) {
                showError('Please fill in all required fields');
                return;
            }

            // Show loading
            document.getElementById('loadingOverlay').classList.add('active');

            // Submit form
            const formData = new FormData(this);

            fetch('generate.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text || 'Generation failed');
                    });
                }
                return response.blob();
            })
            .then(blob => {
                // Get filename from header or use default
                const downloadType = formData.get('download_type');
                let filename = 'cover_page';

                if (downloadType === 'pdf') {
                    filename += '.pdf';
                } else if (downloadType === 'docx') {
                    filename += '.docx';
                } else {
                    filename += '.zip';
                }

                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                // Hide loading
                document.getElementById('loadingOverlay').classList.remove('active');

                // Show success message
                alert('✅ Your document has been generated and downloaded successfully!');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loadingOverlay').classList.remove('active');
                showError(error.message || 'Failed to generate document. Please try again.');
            });
        });

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.classList.add('show');

            setTimeout(() => {
                errorDiv.classList.remove('show');
            }, 5000);
        }
    </script>
</body>
</html>
