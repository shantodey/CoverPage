<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance();
    echo "✅ Database connected successfully!<br><br>";

    // Check templates table
    $templates = $db->fetchAll("SELECT * FROM templates");
    echo "📋 Templates in database: " . count($templates) . "<br><br>";

    if (count($templates) > 0) {
        echo "<strong>Templates:</strong><br>";
        foreach ($templates as $template) {
            echo "- ID: {$template['id']}, Name: {$template['name']}, Active: {$template['is_active']}<br>";
        }
    } else {
        echo "⚠️ No templates found in database!<br>";
        echo "You need to:<br>";
        echo "1. Run config/setup.sql in phpMyAdmin<br>";
        echo "2. Or upload a template via admin panel<br>";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
