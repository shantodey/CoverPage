<?php
/**
 * Download the sample template
 */

$filePath = __DIR__ . '/templates/Sample_Template_With_Placeholders.docx';

if (!file_exists($filePath)) {
    die('Sample template not created yet. Run CREATE_SAMPLE_TEMPLATE.php first.');
}

// Clear any output
if (ob_get_level()) {
    ob_end_clean();
}

// Send file
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="Sample_Template_With_Placeholders.docx"');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
?>
