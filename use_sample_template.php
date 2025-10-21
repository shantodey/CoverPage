<?php
/**
 * Switch to using the sample template
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "<h2>Switch to Sample Template</h2>";

try {
    $db = Database::getInstance();

    // Update the template filename
    $db->query(
        "UPDATE templates SET filename = ? WHERE id = 1",
        ['Sample_Template_With_Placeholders.docx']
    );

    echo "✅ <strong>Database updated successfully!</strong><br><br>";

    // Verify the change
    $template = $db->fetchOne("SELECT * FROM templates WHERE id = 1");

    echo "Current template settings:<br>";
    echo "- ID: " . $template['id'] . "<br>";
    echo "- Name: " . htmlspecialchars($template['name']) . "<br>";
    echo "- Filename: <strong>" . htmlspecialchars($template['filename']) . "</strong><br>";
    echo "- Active: " . ($template['is_active'] ? 'Yes' : 'No') . "<br><br>";

    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to <a href='public/check_template_placeholders.php'>Check Template Placeholders</a> to verify</li>";
    echo "<li>Try the form at <a href='public/'>Main Form</a></li>";
    echo "<li>Your form submissions should now be filled with data!</li>";
    echo "</ol>";

} catch (Exception $e) {
    echo "<p style='color: red;'><strong>ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
</style>
