<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Form POST Test</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/config/database.php';

    $db = Database::getInstance();
    $templateId = intval($_POST['template_id'] ?? 0);

    echo "<h3>Template ID received: $templateId</h3>";

    if ($templateId > 0) {
        $template = $db->fetchOne("SELECT * FROM templates WHERE id = ? AND is_active = 1", [$templateId]);

        if ($template) {
            echo "✅ Template found: " . $template['name'];
        } else {
            echo "❌ Template not found in database";
        }
    } else {
        echo "❌ Invalid template ID";
    }
}
?>

<form method="POST">
    <label>Template ID: <input type="number" name="template_id" value="1"></label><br><br>
    <label>Student Name: <input type="text" name="student_name" value="Test Student"></label><br><br>
    <button type="submit">Test Submit</button>
</form>
