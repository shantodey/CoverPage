<?php
/**
 * Test Template Merge - Debug Version
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use PhpOffice\PhpWord\TemplateProcessor;

echo "<h2>Template Merge Test</h2>";

try {
    // Get template
    $db = Database::getInstance();
    $template = $db->fetchOne("SELECT * FROM templates WHERE id = 1 AND is_active = 1");

    if (!$template) {
        die("❌ Template not found in database");
    }

    echo "✅ Template found: " . htmlspecialchars($template['name']) . "<br>";
    echo "Filename: " . htmlspecialchars($template['filename']) . "<br><br>";

    $templatePath = __DIR__ . '/../templates/' . $template['filename'];

    if (!file_exists($templatePath)) {
        die("❌ Template file not found at: " . htmlspecialchars($templatePath));
    }

    echo "✅ Template file exists<br>";
    echo "Path: " . htmlspecialchars($templatePath) . "<br><br>";

    // Load template
    $tpl = new TemplateProcessor($templatePath);

    // Get variables from template
    echo "<h3>Variables found in template:</h3>";
    $variables = $tpl->getVariables();
    echo "<pre>";
    print_r($variables);
    echo "</pre>";

    // Test data
    $testData = [
        'STUDENT_NAME' => 'John Doe',
        'STUDENT_INDEX' => 'CST-M-1914',
        'BOARD_ROLL' => '759291',
        'SEMESTER' => '6th',
        'BATCH' => '2019',
        'SUBJECT_CODE' => 'CST-301',
        'SUBJECT_NAME' => 'Web Technology',
        'EXPERIMENT_NAME' => 'Creating a Form',
        'ASSIGNMENT_NO' => '01',
        'SESSION' => '2024-25',
        'DATE_OF_EXPT' => '2025-10-15',
        'SUBMISSION_DATE' => '2025-10-21',
        'TEACHER_NAME' => 'Dr. Smith',
        'TEACHER_DESIGNATION' => 'Professor',
        'DEPARTMENT' => 'Computer Science & Technology'
    ];

    echo "<h3>Test data to merge:</h3><pre>";
    print_r($testData);
    echo "</pre>";

    // Try to replace each variable
    echo "<h3>Replacement Results:</h3>";
    foreach ($testData as $placeholder => $value) {
        try {
            $tpl->setValue($placeholder, $value);
            echo "✅ Set {$placeholder} = {$value}<br>";
        } catch (Exception $e) {
            echo "❌ Failed to set {$placeholder}: " . $e->getMessage() . "<br>";
        }
    }

    // Save to temp file
    $outputPath = sys_get_temp_dir() . '/test_merge_output.docx';
    $tpl->saveAs($outputPath);

    if (file_exists($outputPath)) {
        echo "<br><h3>✅ File saved successfully!</h3>";
        echo "File size: " . filesize($outputPath) . " bytes<br>";
        echo "<a href='download_test.php' target='_blank'>Download Test File</a><br>";

        // Save path for download script
        file_put_contents(sys_get_temp_dir() . '/test_merge_path.txt', $outputPath);
    } else {
        echo "<br>❌ Failed to save file";
    }

} catch (Exception $e) {
    echo "<br><br>❌ <strong>ERROR:</strong> " . htmlspecialchars($e->getMessage());
    echo "<br><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>
