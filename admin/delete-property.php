<?php
/**
 * Abdullah Property Dealer - Delete Property Action
 */
require_once __DIR__ . '/../includes/config.php';
require_admin();

$id = $_GET['id'] ?? '';
$token = $_GET['token'] ?? '';

if (!verify_csrf_token($token)) {
    set_flash('error', 'Security verification failed (invalid CSRF token).');
    header('Location: properties.php');
    exit;
}

if (!empty($id)) {
    $props = get_properties();
    $found = false;
    $remaining = [];

    foreach ($props as $p) {
        if ($p['id'] === $id) {
            $found = true;
            // If image is inside uploads/, we can clean it up safely
            if (!empty($p['image']) && str_starts_with($p['image'], 'uploads/')) {
                $file = BASE_DIR . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p['image']);
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
        } else {
            $remaining[] = $p;
        }
    }

    if ($found) {
        save_properties($remaining);
        set_flash('success', "Property {$id} was permanently deleted.");
    } else {
        set_flash('error', "Property {$id} was not found.");
    }
}

header('Location: properties.php');
exit;

