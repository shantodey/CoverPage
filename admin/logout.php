<?php
/**
 * Admin Logout
 * Cover Page - Document Generator
 */

require_once __DIR__ . '/auth.php';

$auth = new Auth();
$auth->logout();

header('Location: cp-secure-entry.php');
exit;
