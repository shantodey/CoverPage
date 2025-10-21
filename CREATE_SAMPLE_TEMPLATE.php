<?php
/**
 * Create a Sample Template with Placeholders
 * Run this script to generate a sample Word template with all the correct placeholders
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

echo "<h2>Sample Template Generator</h2>";

try {
    $phpWord = new PhpWord();

    // Add a section
    $section = $phpWord->addSection([
        'marginTop' => 1000,
        'marginBottom' => 1000,
        'marginLeft' => 1440,
        'marginRight' => 1440,
    ]);

    // Header - Institution Name (you can customize this)
    $section->addText(
        'YOUR INSTITUTION NAME HERE',
        ['bold' => true, 'size' => 16, 'name' => 'Arial'],
        ['alignment' => Jc::CENTER]
    );

    $section->addText(
        'Department of ${DEPARTMENT}',
        ['bold' => true, 'size' => 14, 'name' => 'Arial'],
        ['alignment' => Jc::CENTER]
    );

    $section->addTextBreak(1);

    // Title
    $section->addText(
        'JOB SHEET COVER PAGE',
        ['bold' => true, 'size' => 18, 'name' => 'Arial', 'underline' => 'single'],
        ['alignment' => Jc::CENTER]
    );

    $section->addTextBreak(2);

    // Student Information
    $section->addText('STUDENT INFORMATION', ['bold' => true, 'size' => 12, 'underline' => 'single']);
    $section->addTextBreak(1);

    $section->addText('Name: ${STUDENT_NAME}', ['size' => 11]);
    $section->addText('Index No.: ${STUDENT_INDEX}', ['size' => 11]);
    $section->addText('Board Roll: ${BOARD_ROLL}', ['size' => 11]);
    $section->addText('Semester: ${SEMESTER}', ['size' => 11]);
    $section->addText('Batch: ${BATCH}', ['size' => 11]);

    $section->addTextBreak(1);

    // Subject Information
    $section->addText('SUBJECT INFORMATION', ['bold' => true, 'size' => 12, 'underline' => 'single']);
    $section->addTextBreak(1);

    $section->addText('Subject Code: ${SUBJECT_CODE}', ['size' => 11]);
    $section->addText('Subject Name: ${SUBJECT_NAME}', ['size' => 11]);

    $section->addTextBreak(1);

    // Assignment Information
    $section->addText('ASSIGNMENT INFORMATION', ['bold' => true, 'size' => 12, 'underline' => 'single']);
    $section->addTextBreak(1);

    $section->addText('Assignment No.: ${ASSIGNMENT_NO}', ['size' => 11]);
    $section->addText('Session: ${SESSION}', ['size' => 11]);
    $section->addText('Name of Experiment: ${EXPERIMENT_NAME}', ['size' => 11]);
    $section->addText('Date of Experiment: ${DATE_OF_EXPT}', ['size' => 11]);
    $section->addText('Submission Date: ${SUBMISSION_DATE}', ['size' => 11]);

    $section->addTextBreak(1);

    // Teacher Information
    $section->addText('TEACHER INFORMATION', ['bold' => true, 'size' => 12, 'underline' => 'single']);
    $section->addTextBreak(1);

    $section->addText('Teacher Name: ${TEACHER_NAME}', ['size' => 11]);
    $section->addText('Designation: ${TEACHER_DESIGNATION}', ['size' => 11]);

    $section->addTextBreak(3);

    // Signature area
    $section->addText('_____________________', ['size' => 11], ['alignment' => Jc::END]);
    $section->addText('Teacher Signature', ['size' => 10, 'italic' => true], ['alignment' => Jc::END]);

    // Save the template
    $outputPath = __DIR__ . '/templates/Sample_Template_With_Placeholders.docx';

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($outputPath);

    if (file_exists($outputPath)) {
        echo "✅ <strong>Sample template created successfully!</strong><br><br>";
        echo "Location: <code>" . htmlspecialchars($outputPath) . "</code><br><br>";
        echo "File size: " . number_format(filesize($outputPath)) . " bytes<br><br>";

        echo "<h3>Next Steps:</h3>";
        echo "<ol>";
        echo "<li>Download and open this sample template to see how placeholders should look</li>";
        echo "<li>Edit your existing template (<code>Jobe-Sheet-cover_CST.docx</code>) to add placeholders</li>";
        echo "<li>Or use this sample template instead by updating the database</li>";
        echo "</ol>";

        echo "<br><a href='download_sample_template.php' style='display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>Download Sample Template</a>";

        echo "<br><br><h3>To use this sample template:</h3>";
        echo "<p>Run this SQL in your database:</p>";
        echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
        echo "UPDATE templates \nSET filename = 'Sample_Template_With_Placeholders.docx' \nWHERE id = 1;";
        echo "</pre>";

        echo "<br><p><strong>Or</strong> open your current template and add the placeholders manually following the guide in <a href='HOW_TO_ADD_PLACEHOLDERS.md'>HOW_TO_ADD_PLACEHOLDERS.md</a></p>";
    } else {
        echo "❌ Failed to create template file";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'><strong>ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
    code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
</style>
