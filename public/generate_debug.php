<?php
/**
 * Document Generation Handler - DEBUG VERSION
 */

// ENABLE ERROR DISPLAY FOR DEBUGGING
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Generate Debug</h2>";
echo "<h3>POST Data:</h3><pre>";
print_r($_POST);
echo "</pre>";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use PhpOffice\PhpWord\TemplateProcessor;

try {
    $db = Database::getInstance();
    echo "✅ Database connected<br>";

    $templateId = intval($_POST['template_id'] ?? 0);
    echo "Template ID: $templateId<br>";

    if ($templateId <= 0) {
        die("❌ Invalid template ID");
    }

    $template = $db->fetchOne("SELECT * FROM templates WHERE id = ? AND is_active = 1", [$templateId]);

    if (!$template) {
        die("❌ Template not found in database");
    }

    echo "✅ Template found: " . $template['name'] . "<br>";

    $templatePath = __DIR__ . '/../templates/' . $template['filename'];
    echo "Template path: $templatePath<br>";

    if (!file_exists($templatePath)) {
        die("❌ Template file does not exist at: $templatePath");
    }

    echo "✅ Template file exists<br>";

    // Check if vendor/autoload worked
    if (!class_exists('PhpOffice\PhpWord\TemplateProcessor')) {
        die("❌ PHPWord TemplateProcessor class not found!");
    }

    echo "✅ PHPWord loaded<br>";

    // Try to process template
    $tpl = new TemplateProcessor($templatePath);
    echo "✅ Template processor created<br>";

    echo "<br><strong>✅ Everything looks good! The issue might be in the actual processing.</strong>";

} catch (Exception $e) {
    echo "<br><br>❌ <strong>ERROR:</strong> " . $e->getMessage();
    echo "<br><br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
