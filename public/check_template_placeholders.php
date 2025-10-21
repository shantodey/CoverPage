<?php
/**
 * Check Template Placeholders
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use PhpOffice\PhpWord\TemplateProcessor;

echo "<h2>Template Placeholder Checker</h2>";

try {
    // Get template from database
    $db = Database::getInstance();
    $template = $db->fetchOne("SELECT * FROM templates WHERE id = 1");

    if (!$template) {
        die("❌ Template not found");
    }

    echo "<strong>Template:</strong> " . htmlspecialchars($template['name']) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($template['filename']) . "<br>";
    echo "<strong>Department:</strong> " . htmlspecialchars($template['department']) . "<br><br>";

    $templatePath = __DIR__ . '/../templates/' . $template['filename'];

    if (!file_exists($templatePath)) {
        die("❌ Template file not found: " . htmlspecialchars($templatePath));
    }

    // Load template
    $tpl = new TemplateProcessor($templatePath);

    // Get all variables
    $variables = $tpl->getVariables();

    echo "<h3>Placeholders Found in Template:</h3>";

    if (empty($variables)) {
        echo "<p style='color: red;'><strong>⚠️ NO PLACEHOLDERS FOUND!</strong></p>";
        echo "<p>This means the template doesn't have any {{PLACEHOLDER}} markers.</p>";
    } else {
        echo "<p>Found <strong>" . count($variables) . "</strong> unique placeholder(s):</p>";
        echo "<ol>";
        foreach ($variables as $var) {
            echo "<li><code>{{" . htmlspecialchars($var) . "}}</code></li>";
        }
        echo "</ol>";

        echo "<h3>Expected Placeholders:</h3>";
        $expected = [
            'STUDENT_NAME',
            'STUDENT_INDEX',
            'BOARD_ROLL',
            'SEMESTER',
            'BATCH',
            'SUBJECT_CODE',
            'SUBJECT_NAME',
            'EXPERIMENT_NAME',
            'ASSIGNMENT_NO',
            'SESSION',
            'DATE_OF_EXPT',
            'SUBMISSION_DATE',
            'TEACHER_NAME',
            'TEACHER_DESIGNATION',
            'DEPARTMENT'
        ];

        echo "<h4>Comparison:</h4>";
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
        echo "<tr><th>Expected Placeholder</th><th>Status</th></tr>";

        foreach ($expected as $exp) {
            $found = in_array($exp, $variables);
            $status = $found ? "✅ Found" : "❌ Missing";
            $color = $found ? "green" : "red";
            echo "<tr><td><code>{{" . $exp . "}}</code></td><td style='color: {$color};'>{$status}</td></tr>";
        }

        echo "</table>";

        // Check for extra placeholders
        $extra = array_diff($variables, $expected);
        if (!empty($extra)) {
            echo "<h4>⚠️ Extra placeholders in template (not used by form):</h4>";
            echo "<ul>";
            foreach ($extra as $e) {
                echo "<li><code>{{" . htmlspecialchars($e) . "}}</code></li>";
            }
            echo "</ul>";
        }
    }

} catch (Exception $e) {
    echo "<p style='color: red;'><strong>ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
</style>
