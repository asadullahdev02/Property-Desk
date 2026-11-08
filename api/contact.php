<?php
/**
 * Abdullah Property Dealer - Inquiry Submission Handler
 * Validates and saves customer property inquiries into data/inquiries.json
 */

require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

// Extract and sanitize input fields
$name = trim(sanitize_input($_POST['name'] ?? ''));
$address = trim(sanitize_input($_POST['address'] ?? ''));
$phone_raw = trim(sanitize_input($_POST['phone'] ?? ''));
$message = trim(sanitize_input($_POST['message'] ?? ''));
$property_id = trim(sanitize_input($_POST['property_id'] ?? 'General Inquiry'));
$property_title = trim(sanitize_input($_POST['property_title'] ?? 'General Agency Inquiry'));

// Field validations
$errors = [];

if (empty($name)) {
    $errors[] = 'Please enter your full name.';
}

if (empty($address)) {
    $errors[] = 'Please enter your address.';
}

if (empty($phone_raw)) {
    $errors[] = 'Please enter your contact phone number.';
}

if (empty($message)) {
    $errors[] = 'Please enter your message or question.';
}

// Format Pakistani phone number (+92 prefix if omitted)
$phone = $phone_raw;
if (!str_starts_with($phone, '+92')) {
    // If entered as 0300..., convert to +92 300...
    $cleaned = preg_replace('/[^0-9]/', '', $phone);
    if (str_starts_with($cleaned, '03')) {
        $phone = '+92 ' . substr($cleaned, 1);
    } elseif (str_starts_with($cleaned, '92')) {
        $phone = '+' . $cleaned;
    } else {
        $phone = '+92 ' . $phone;
    }
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// Prepare new inquiry record
$inquiry_id = 'INQ-' . date('Ymd') . '-' . substr(strtoupper(bin2hex(random_bytes(2))), 0, 4);
$new_inquiry = [
    'id' => $inquiry_id,
    'name' => $name,
    'phone' => $phone,
    'address' => $address,
    'property_id' => $property_id,
    'property_title' => $property_title,
    'message' => $message,
    'status' => 'New',
    'created_at' => date('Y-m-d H:i:s')
];

// Save to data/inquiries.json
$saved = add_inquiry($new_inquiry);

// Construct formatted WhatsApp message URL for direct delivery to agent (+92 3024625625)
$wa_lines = [
    "Assalam-o-Alaikum Abdullah Property Dealer,",
    "",
    "*New Website Inquiry*",
    "• Name: " . $name,
    "• Phone: " . $phone,
    "• Address: " . $address
];

if (!empty($property_title) && $property_title !== 'General Agency Contact') {
    $prop_line = "• Property: " . $property_title;
    if (!empty($property_id) && $property_id !== 'General Inquiry') {
        $prop_line .= " (ID: " . $property_id . ")";
    }
    $wa_lines[] = $prop_line;
}

$wa_lines[] = "• Message: " . $message;
$wa_lines[] = "";
$wa_lines[] = "(Inquiry ID: " . $inquiry_id . ")";

$wa_text = implode("\n", $wa_lines);
$whatsapp_url = "https://wa.me/" . AGENT_WHATSAPP . "?text=" . rawurlencode($wa_text);

if ($saved) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you, ' . $name . '! Your inquiry (' . $inquiry_id . ') has been recorded and is being forwarded to WhatsApp (+92 3024625625)...',
        'inquiry_id' => $inquiry_id,
        'whatsapp_url' => $whatsapp_url
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Unable to record your inquiry due to a temporary storage issue. Please call us directly.'
    ]);
}

