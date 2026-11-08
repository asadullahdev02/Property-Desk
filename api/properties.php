<?php
/**
 * Abdullah Property Dealer - Properties JSON API
 * Provides structured property listings data for AJAX and external consumers
 */

require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json; charset=UTF-8');

$properties = get_properties();

// Optional filter query params
$city = strtolower(trim($_GET['city'] ?? 'all'));
$category = strtolower(trim($_GET['type'] ?? 'all'));
$purpose = strtolower(trim($_GET['purpose'] ?? 'all'));
$min_price = (float)($_GET['min_price'] ?? 0);
$max_price = !empty($_GET['max_price']) ? (float)$_GET['max_price'] : PHP_FLOAT_MAX;
$status = strtolower(trim($_GET['status'] ?? 'all'));

$filtered = array_values(array_filter($properties, function($p) use ($city, $category, $purpose, $min_price, $max_price, $status) {
    if ($city !== 'all' && strtolower($p['city']) !== $city) return false;
    if ($category !== 'all' && strtolower($p['category']) !== $category) return false;
    if ($purpose !== 'all' && strtolower($p['purpose']) !== $purpose) return false;
    if ($p['price'] < $min_price || $p['price'] > $max_price) return false;
    if ($status !== 'all' && strtolower($p['status']) !== $status) return false;
    return true;
}));

echo json_encode([
    'success' => true,
    'total' => count($filtered),
    'data' => $filtered
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

