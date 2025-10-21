<?php
/**
 * Document Generation Handler
 * Cover Page - Document Generator
 * Merges form data into template and generates DOCX/PDF
 */

// Start output buffering to prevent any accidental output
ob_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Set error handling - DISABLED to prevent output before headers
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);

// Set max execution time for PDF conversion
set_time_limit(120);

/**
 * Sanitize input data
 */
function sanitizeInput($data, $maxLength = 500) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return mb_substr($data, 0, $maxLength, 'UTF-8');
}

/**
 * Generate unique temporary filename
 */
function getTempFilename($extension = '') {
    $tmpDir = sys_get_temp_dir();
    $uniqueId = uniqid('coverpage_', true) . '_' . bin2hex(random_bytes(8));
    return $tmpDir . DIRECTORY_SEPARATOR . $uniqueId . $extension;
}

/**
 * Convert DOCX to PDF using LibreOffice
 */
function convertToPdf($docxPath, $outputDir) {
    // Check if LibreOffice is available
    $libreOfficePaths = [
        'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
        'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
        '/usr/bin/libreoffice',
        '/usr/bin/soffice',
        'libreoffice',
        'soffice'
    ];

    $libreOfficeCmd = null;
    foreach ($libreOfficePaths as $path) {
        if (file_exists($path) || shell_exec("which $path 2>/dev/null")) {
            $libreOfficeCmd = $path;
            break;
        }
    }

    if (!$libreOfficeCmd) {
        return false;
    }

    // Build conversion command
    $cmd = sprintf(
        '"%s" --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
        $libreOfficeCmd,
        $outputDir,
        $docxPath
    );

    // Execute conversion
    exec($cmd, $output, $returnCode);

    // Check if PDF was created
    $pdfPath = $outputDir . DIRECTORY_SEPARATOR . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';

    if ($returnCode === 0 && file_exists($pdfPath)) {
        return $pdfPath;
    }

    return false;
}

/**
 * Create ZIP archive with multiple files
 */
function createZipArchive($files, $zipPath) {
    $zip = new ZipArchive();

    if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
        return false;
    }

    foreach ($files as $filename => $filepath) {
        if (file_exists($filepath)) {
            $zip->addFile($filepath, $filename);
        }
    }

    $zip->close();
    return file_exists($zipPath);
}

/**
 * Send file for download
 */
function sendFileDownload($filepath, $filename, $contentType) {
    if (!file_exists($filepath)) {
        http_response_code(404);
        exit('File not found');
    }

    // Clear any previous output
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Set headers
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Send file
    readfile($filepath);
    exit;
}

/**
 * Clean up temporary files
 */
function cleanupFiles($files) {
    foreach ($files as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
}

// ============================================================================
// MAIN PROCESSING
// ============================================================================

try {
    // Verify POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Method not allowed');
    }

    // Get and validate template
    $templateId = intval($_POST['template_id'] ?? 0);
    if ($templateId <= 0) {
        http_response_code(400);
        exit('Invalid template');
    }

    // Fetch template from database
    $db = Database::getInstance();
    $template = $db->fetchOne(
        "SELECT * FROM templates WHERE id = ? AND is_active = 1",
        [$templateId]
    );

    if (!$template) {
        http_response_code(404);
        exit('Template not found');
    }

    $templatePath = __DIR__ . '/../templates/' . $template['filename'];

    if (!file_exists($templatePath)) {
        http_response_code(404);
        exit('Template file missing');
    }

    // Collect and sanitize form data
    $formData = [
        'STUDENT_NAME' => sanitizeInput($_POST['student_name'] ?? ''),
        'STUDENT_INDEX' => sanitizeInput($_POST['student_index'] ?? ''),
        'BOARD_ROLL' => sanitizeInput($_POST['board_roll'] ?? ''),
        'SEMESTER' => sanitizeInput($_POST['semester'] ?? ''),
        'BATCH' => sanitizeInput($_POST['batch'] ?? ''),
        'SUBJECT_CODE' => sanitizeInput($_POST['subject_code'] ?? ''),
        'SUBJECT_NAME' => sanitizeInput($_POST['subject_name'] ?? ''),
        'EXPERIMENT_NAME' => sanitizeInput($_POST['experiment_name'] ?? ''),
        'ASSIGNMENT_NO' => sanitizeInput($_POST['assignment_no'] ?? ''),
        'SESSION' => sanitizeInput($_POST['session'] ?? ''),
        'DATE_OF_EXPT' => sanitizeInput($_POST['date_of_expt'] ?? ''),
        'SUBMISSION_DATE' => sanitizeInput($_POST['submission_date'] ?? ''),
        'TEACHER_NAME' => sanitizeInput($_POST['teacher_name'] ?? ''),
        'TEACHER_DESIGNATION' => sanitizeInput($_POST['teacher_designation'] ?? ''),
        'DEPARTMENT' => sanitizeInput($template['department']),
    ];

    // Get download type
    $downloadType = $_POST['download_type'] ?? 'both';
    if (!in_array($downloadType, ['pdf', 'docx', 'both'])) {
        $downloadType = 'both';
    }

    // Generate temporary DOCX
    $tmpDocx = getTempFilename('.docx');
    $filesToCleanup = [$tmpDocx];

    // Process template
    $templateProcessor = new TemplateProcessor($templatePath);

    // Replace all placeholders
    foreach ($formData as $placeholder => $value) {
        try {
            $templateProcessor->setValue($placeholder, $value);
        } catch (Exception $e) {
            // Placeholder might not exist in template, continue
            continue;
        }
    }

    // Save processed DOCX
    $templateProcessor->saveAs($tmpDocx);

    // Handle different download types
    if ($downloadType === 'docx') {
        // Send DOCX only
        sendFileDownload($tmpDocx, 'cover_page.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        cleanupFiles($filesToCleanup);

    } elseif ($downloadType === 'pdf') {
        // Convert to PDF and send
        $tmpDir = dirname($tmpDocx);
        $tmpPdf = convertToPdf($tmpDocx, $tmpDir);

        if ($tmpPdf && file_exists($tmpPdf)) {
            $filesToCleanup[] = $tmpPdf;
            sendFileDownload($tmpPdf, 'cover_page.pdf', 'application/pdf');
            cleanupFiles($filesToCleanup);
        } else {
            // PDF conversion failed, send DOCX as fallback
            cleanupFiles($filesToCleanup);
            http_response_code(500);
            exit('PDF conversion failed. LibreOffice may not be installed. Please download DOCX format instead.');
        }

    } else {
        // Both - create ZIP
        $tmpDir = dirname($tmpDocx);
        $tmpPdf = convertToPdf($tmpDocx, $tmpDir);

        if ($tmpPdf && file_exists($tmpPdf)) {
            $filesToCleanup[] = $tmpPdf;

            // Create ZIP
            $tmpZip = getTempFilename('.zip');
            $filesToCleanup[] = $tmpZip;

            if (createZipArchive([
                'cover_page.docx' => $tmpDocx,
                'cover_page.pdf' => $tmpPdf
            ], $tmpZip)) {
                sendFileDownload($tmpZip, 'cover_page_files.zip', 'application/zip');
                cleanupFiles($filesToCleanup);
            } else {
                cleanupFiles($filesToCleanup);
                http_response_code(500);
                exit('Failed to create ZIP archive');
            }
        } else {
            // PDF conversion failed, send DOCX only
            sendFileDownload($tmpDocx, 'cover_page.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            cleanupFiles($filesToCleanup);
        }
    }

} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log('Cover Page Generation Error: ' . $e->getMessage());

    // Clean up any temporary files
    if (isset($filesToCleanup)) {
        cleanupFiles($filesToCleanup);
    }

    http_response_code(500);
    exit('An error occurred while generating your document. Please try again.');
}
