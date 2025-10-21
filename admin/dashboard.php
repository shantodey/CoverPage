<?php
/**
 * Admin Dashboard
 * Cover Page - Document Generator
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

$auth = new Auth();
$auth->requireAuth();

$db = Database::getInstance();
$admin = $auth->getCurrentAdmin();

// Handle template upload
$uploadMessage = '';
$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'upload_template') {
        $name = trim($_POST['template_name'] ?? '');
        $department = trim($_POST['department'] ?? 'CST');
        $locale = $_POST['locale'] ?? 'en';
        $description = trim($_POST['description'] ?? '');

        if (isset($_FILES['template_file']) && $_FILES['template_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['template_file'];
            $allowedExts = ['docx'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts)) {
                $filename = preg_replace('/[^a-z0-9._-]/i', '_', pathinfo($file['name'], PATHINFO_FILENAME));
                $filename = $filename . '_' . time() . '.docx';
                $destination = __DIR__ . '/../templates/' . $filename;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $sql = "INSERT INTO templates (name, filename, department, locale, description, created_by)
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $db->query($sql, [$name, $filename, $department, $locale, $description, $admin['id']]);

                    $auth->logActivity($admin['id'], 'upload_template', "Uploaded template: $name");
                    $uploadMessage = 'Template uploaded successfully!';
                } else {
                    $uploadError = 'Failed to upload file.';
                }
            } else {
                $uploadError = 'Only .docx files are allowed.';
            }
        } else {
            $uploadError = 'Please select a file to upload.';
        }
    } elseif ($_POST['action'] === 'delete_template') {
        $templateId = intval($_POST['template_id']);
        $template = $db->fetchOne("SELECT * FROM templates WHERE id = ?", [$templateId]);

        if ($template) {
            $filePath = __DIR__ . '/../templates/' . $template['filename'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $db->query("DELETE FROM templates WHERE id = ?", [$templateId]);
            $auth->logActivity($admin['id'], 'delete_template', "Deleted template: {$template['name']}");
            $uploadMessage = 'Template deleted successfully!';
        }
    } elseif ($_POST['action'] === 'toggle_active') {
        $templateId = intval($_POST['template_id']);
        $db->query("UPDATE templates SET is_active = NOT is_active WHERE id = ?", [$templateId]);
        $uploadMessage = 'Template status updated!';
    }
}

// Fetch all templates
$templates = $db->fetchAll("SELECT * FROM templates ORDER BY created_at DESC");

// Fetch recent logs
$logs = $db->fetchAll("SELECT al.*, au.username FROM admin_logs al
                       LEFT JOIN admin_users au ON al.admin_id = au.id
                       ORDER BY al.created_at DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cover Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-info {
            text-align: right;
        }

        .admin-info span {
            opacity: 0.9;
            font-size: 14px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 30px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #ef4444;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 18px;
            font-weight: 700;
        }

        .card-body {
            padding: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .file-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .file-upload input {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>📄 Cover Page Admin</h1>
            <div class="header-right">
                <div class="admin-info">
                    <span>Welcome, <strong><?php echo htmlspecialchars($admin['name'] ?? $admin['username']); ?></strong></span>
                </div>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($uploadMessage): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($uploadMessage); ?></div>
        <?php endif; ?>

        <?php if ($uploadError): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($uploadError); ?></div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Templates</h3>
                <div class="number"><?php echo count($templates); ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Templates</h3>
                <div class="number"><?php echo count(array_filter($templates, fn($t) => $t['is_active'])); ?></div>
            </div>
            <div class="stat-card">
                <h3>Departments</h3>
                <div class="number"><?php echo count(array_unique(array_column($templates, 'department'))); ?></div>
            </div>
            <div class="stat-card">
                <h3>Recent Activities</h3>
                <div class="number"><?php echo count($logs); ?></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="../public/index.php" target="_blank" class="btn btn-primary">View Public Form</a>
            <a href="#upload" class="btn btn-success">Upload New Template</a>
        </div>

        <!-- Upload Template Form -->
        <div class="card" id="upload">
            <div class="card-header">
                <h2>Upload New Template</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload_template">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="template_name">Template Name *</label>
                            <input type="text" id="template_name" name="template_name" required placeholder="e.g., Job Sheet Cover - CST">
                        </div>

                        <div class="form-group">
                            <label for="department">Department *</label>
                            <input type="text" id="department" name="department" value="CST" required placeholder="e.g., CST, Civil, Mechanical">
                        </div>

                        <div class="form-group">
                            <label for="locale">Language *</label>
                            <select id="locale" name="locale">
                                <option value="en">English</option>
                                <option value="bn">Bengali</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Describe this template..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload .docx Template *</label>
                        <div class="file-upload" onclick="document.getElementById('template_file').click()">
                            <p style="color: #64748b; margin-bottom: 8px;">📁 Click to select .docx file</p>
                            <p style="font-size: 12px; color: #94a3b8;">Only .docx files are allowed</p>
                            <input type="file" id="template_file" name="template_file" accept=".docx" required onchange="document.querySelector('.file-upload p').textContent = this.files[0].name">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Template</button>
                </form>
            </div>
        </div>

        <!-- Templates List -->
        <div class="card">
            <div class="card-header">
                <h2>All Templates</h2>
            </div>
            <div class="card-body" style="padding: 0;">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Language</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($templates as $template): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($template['name']); ?></strong></td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($template['department']); ?></span></td>
                                <td><?php echo strtoupper($template['locale']); ?></td>
                                <td>
                                    <?php if ($template['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($template['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="../templates/<?php echo htmlspecialchars($template['filename']); ?>" download class="btn btn-primary btn-small">Download</a>

                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="toggle_active">
                                            <input type="hidden" name="template_id" value="<?php echo $template['id']; ?>">
                                            <button type="submit" class="btn btn-warning btn-small">
                                                <?php echo $template['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>

                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this template?')" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_template">
                                            <input type="hidden" name="template_id" value="<?php echo $template['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="card">
            <div class="card-header">
                <h2>Recent Activity</h2>
            </div>
            <div class="card-body" style="padding: 0;">
                <table>
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log['username']); ?></td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                <td><?php echo htmlspecialchars($log['details']); ?></td>
                                <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
