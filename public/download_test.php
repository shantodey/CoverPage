<?php
/**
 * Download test file
 */

$pathFile = sys_get_temp_dir() . '/test_merge_path.txt';

if (!file_exists($pathFile)) {
    die('Test file not generated yet. Run generate_test_merge.php first.');
}

$filePath = trim(file_get_contents($pathFile));

if (!file_exists($filePath)) {
    die('Test file not found: ' . $filePath);
}

// Clear any output
if (ob_get_level()) {
    ob_end_clean();
}

// Send file
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="test_merge_output.docx"');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
?>
