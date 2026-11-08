<?php
/**
 * Abdullah Property Dealer - Core Configuration & Helpers
 * Clean, lightweight, beginner-friendly PHP codebase.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Application Constants
define('APP_NAME', 'Abdullah Property Dealer');
define('APP_INITIALS', 'APD');
define('APP_TAGLINE', 'Your Trusted Real Estate Partner in Pakistan');
define('APP_LOCATION', 'Pakistan');

// Configurable Agent Contact Details
define('AGENT_PHONE', '+92 3024625625');
define('AGENT_WHATSAPP', '923024625625');
define('AGENT_EMAIL', 'contact@abdullahpropertydealer.pk');
define('AGENT_ADDRESS', 'Office 402, Al-Hafeez Heights, Ghalib Rd, Gulberg III, Lahore, Pakistan');

// Base Paths
define('BASE_DIR', dirname(__DIR__));
define('DATA_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'data');
define('UPLOADS_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'uploads');

// Ensure directories exist
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}
if (!is_dir(UPLOADS_DIR)) {
    mkdir(UPLOADS_DIR, 0755, true);
}

/**
 * Load Properties from JSON
 */
function get_properties() {
    $file = DATA_DIR . DIRECTORY_SEPARATOR . 'properties.json';
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/**
 * Find Property by ID or Slug
 */
function get_property_by_id($id) {
    $properties = get_properties();
    foreach ($properties as $prop) {
        if ($prop['id'] === $id || (isset($prop['slug']) && $prop['slug'] === $id)) {
            return $prop;
        }
    }
    return null;
}

/**
 * Save Properties to JSON
 */
function save_properties($properties) {
    $file = DATA_DIR . DIRECTORY_SEPARATOR . 'properties.json';
    return file_put_contents($file, json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) !== false;
}

/**
 * Load Inquiries from JSON
 */
function get_inquiries() {
    $file = DATA_DIR . DIRECTORY_SEPARATOR . 'inquiries.json';
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/**
 * Save Inquiries to JSON
 */
function save_inquiries($inquiries) {
    $file = DATA_DIR . DIRECTORY_SEPARATOR . 'inquiries.json';
    return file_put_contents($file, json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) !== false;
}

/**
 * Add New Inquiry
 */
function add_inquiry($inquiry) {
    $inquiries = get_inquiries();
    array_unshift($inquiries, $inquiry);
    return save_inquiries($inquiries);
}

/**
 * Load Admin Credentials
 */
function get_admin_data() {
    $file = DATA_DIR . DIRECTORY_SEPARATOR . 'admin.json';
    if (!file_exists($file)) {
        return null;
    }
    $content = file_get_contents($file);
    return json_decode($content, true);
}

/**
 * Verify Admin Authentication
 */
function verify_admin_login($username, $password) {
    $admin = get_admin_data();
    if (!$admin) {
        return false;
    }
    if (trim($username) === $admin['username'] && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $admin['username'];
        $_SESSION['admin_name'] = $admin['name'] ?? 'Admin';
        $_SESSION['admin_role'] = $admin['role'] ?? 'Administrator';
        return true;
    }
    return false;
}

/**
 * Check if admin is authenticated
 */
function is_admin_logged_in() {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Restrict page to admin only
 */
function require_admin() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Sanitize user input
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    $data = trim((string)$data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Format Pakistani Rupees (PKR)
 * Converts numbers to Crores, Lakhs, or thousands with standard Pakistani notation
 */
function format_pkr($amount, $purpose = 'sale') {
    $val = (float)$amount;
    if ($val <= 0) return 'Price on Request';

    $suffix = '';
    if (strtolower($purpose) === 'rent') {
        $suffix = ' / Month';
    }

    if ($val >= 10000000) {
        $crore = $val / 10000000;
        return 'PKR ' . (round($crore, 2) == round($crore, 0) ? number_format($crore, 0) : number_format($crore, 2)) . ' Crore' . $suffix;
    } elseif ($val >= 100000) {
        $lakh = $val / 100000;
        return 'PKR ' . (round($lakh, 2) == round($lakh, 0) ? number_format($lakh, 0) : number_format($lakh, 2)) . ' Lakh' . $suffix;
    } else {
        return 'PKR ' . number_format($val) . $suffix;
    }
}

/**
 * CSRF Protection
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Flash Messages
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // 'success' or 'error'
        'message' => $message
    ];
}

function get_flash() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Image Upload Handler
 */
function handle_image_upload($file) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Invalid upload parameters.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error code: ' . $file['error']];
    }

    // Size limit: 5 MB
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'error' => 'File size exceeds maximum 5MB limit.'];
    }

    // MIME verification
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed_mimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!array_key_exists($mime, $allowed_mimes)) {
        return ['success' => false, 'error' => 'Only JPG, PNG, and WebP images are permitted.'];
    }

    $ext = $allowed_mimes[$mime];
    $filename = 'prop_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destination = UPLOADS_DIR . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Failed to save uploaded file on server.'];
    }

    return [
        'success' => true,
        'filepath' => 'uploads/' . $filename,
        'filename' => $filename
    ];
}

/**
 * Statistics calculation for Admin Dashboard
 */
function get_dashboard_stats() {
    $properties = get_properties();
    $inquiries = get_inquiries();

    $stats = [
        'total_properties' => count($properties),
        'available' => 0,
        'sold' => 0,
        'rented' => 0,
        'under_offer' => 0,
        'total_inquiries' => count($inquiries),
        'new_inquiries' => 0
    ];

    foreach ($properties as $prop) {
        $status = strtolower($prop['status'] ?? 'available');
        if ($status === 'available') {
            $stats['available']++;
        } elseif ($status === 'sold') {
            $stats['sold']++;
        } elseif ($status === 'rented') {
            $stats['rented']++;
        } elseif ($status === 'under offer') {
            $stats['under_offer']++;
        }
    }

    foreach ($inquiries as $inq) {
        if (strtolower($inq['status'] ?? '') === 'new') {
            $stats['new_inquiries']++;
        }
    }

    return $stats;
}

