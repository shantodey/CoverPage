<?php
/**
 * Admin Authentication Handler
 * Cover Page - Document Generator
 */

session_start();
require_once __DIR__ . '/../config/database.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Login user
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM admin_users WHERE username = ? AND is_active = 1";
        $user = $this->db->fetchOne($sql, [$username]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_name'] = $user['full_name'];

            // Update last login
            $this->db->query("UPDATE admin_users SET last_login = NOW() WHERE id = ?", [$user['id']]);

            // Log activity
            $this->logActivity($user['id'], 'login', 'Admin logged in');

            return true;
        }

        return false;
    }

    /**
     * Logout user
     */
    public function logout() {
        if (isset($_SESSION['admin_id'])) {
            $this->logActivity($_SESSION['admin_id'], 'logout', 'Admin logged out');
        }

        session_unset();
        session_destroy();
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    /**
     * Require authentication
     */
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $this->getLoginUrl());
            exit;
        }
    }

    /**
     * Get hidden login URL
     */
    public function getLoginUrl() {
        return '/admin/cp-secure-entry.php';
    }

    /**
     * Log admin activity
     */
    public function logActivity($adminId, $action, $details = '') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $sql = "INSERT INTO admin_logs (admin_id, action, details, ip_address) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$adminId, $action, $details, $ip]);
    }

    /**
     * Get current admin info
     */
    public function getCurrentAdmin() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'name' => $_SESSION['admin_name']
        ];
    }
}
