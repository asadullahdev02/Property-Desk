<?php
/**
 * Abdullah Property Dealer - Add / Edit Property Form
 * Handles data validation, image uploads, and JSON updates
 */
require_once __DIR__ . '/../includes/config.php';
require_admin();

$edit_id = $_GET['id'] ?? '';
$is_edit = !empty($edit_id);
$property = null;

if ($is_edit) {
    $property = get_property_by_id($edit_id);
    if (!$property) {
        set_flash('error', 'Property not found.');
        header('Location: properties.php');
        exit;
    }
    $admin_title = 'Edit Property: ' . $property['id'];
    $admin_subtitle = 'Update property details, status, valuation, or photos.';
} else {
    $admin_title = 'Add New Property';
    $admin_subtitle = 'Publish a new real estate listing to the Abdullah Property Dealer portfolio.';
}

$admin_page = $is_edit ? 'properties' : 'add-property';
$errors = [];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim(sanitize_input($_POST['title'] ?? ''));
    $category = trim(sanitize_input($_POST['category'] ?? 'Houses'));
    $purpose = trim(sanitize_input($_POST['purpose'] ?? 'sale'));
    $city = trim(sanitize_input($_POST['city'] ?? 'Lahore'));
    $location = trim(sanitize_input($_POST['location'] ?? ''));
    $price = (float)($_POST['price'] ?? 0);
    $area_value = (float)($_POST['area_value'] ?? 1);
    $area_unit = trim(sanitize_input($_POST['area_unit'] ?? 'Marla'));
    $covered_area = trim(sanitize_input($_POST['covered_area'] ?? ''));
    $bedrooms = (int)($_POST['bedrooms'] ?? 0);
    $bathrooms = (int)($_POST['bathrooms'] ?? 0);
    $status = trim(sanitize_input($_POST['status'] ?? 'Available'));
    $featured = !empty($_POST['featured']);
    $description = trim(sanitize_input($_POST['description'] ?? ''));
    $amenities_input = $_POST['amenities'] ?? [];

    if (empty($title)) $errors[] = 'Property title is required.';
    if (empty($location)) $errors[] = 'Property address and location are required.';
    if ($price <= 0) $errors[] = 'Please specify a valid price in PKR.';
    if (empty($description)) $errors[] = 'Please provide a property description.';

    // Primary Image Handling
    $image_path = $property['image'] ?? 'assets/images/house-1.jpg';
    $gallery = $property['gallery'] ?? [$image_path];

    if (!empty($_FILES['image']['name'])) {
        $upload_res = handle_image_upload($_FILES['image']);
        if ($upload_res['success']) {
            $image_path = $upload_res['filepath'];
            array_unshift($gallery, $image_path);
            $gallery = array_values(array_unique($gallery));
        } else {
            $errors[] = 'Image upload failed: ' . $upload_res['error'];
        }
    }

    if (empty($errors)) {
        $props = get_properties();
        $formatted_price = format_pkr($price, $purpose);

        if ($is_edit) {
            // Update existing
            foreach ($props as &$p) {
                if ($p['id'] === $property['id']) {
                    $p['title'] = $title;
                    $p['category'] = $category;
                    $p['purpose'] = $purpose;
                    $p['city'] = $city;
                    $p['location'] = $location;
                    $p['price'] = $price;
                    $p['price_display'] = $formatted_price;
                    $p['area_value'] = $area_value;
                    $p['area_unit'] = $area_unit;
                    $p['covered_area'] = $covered_area ?: ($area_value . ' ' . $area_unit);
                    $p['bedrooms'] = $bedrooms;
                    $p['bathrooms'] = $bathrooms;
                    $p['status'] = $status;
                    $p['featured'] = $featured;
                    $p['image'] = $image_path;
                    $p['gallery'] = $gallery;
                    $p['description'] = $description;
                    $p['amenities'] = is_array($amenities_input) ? array_values($amenities_input) : [];
                    break;
                }
            }
            save_properties($props);
            set_flash('success', "Property {$property['id']} successfully updated.");
        } else {
            // Generate new ID (e.g. APD-113)
            $new_num = count($props) + 101;
            $new_id = 'APD-' . $new_num;

            $new_property = [
                'id' => $new_id,
                'title' => $title,
                'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))),
                'category' => $category,
                'purpose' => $purpose,
                'city' => $city,
                'location' => $location,
                'price' => $price,
                'price_display' => $formatted_price,
                'area_value' => $area_value,
                'area_unit' => $area_unit,
                'covered_area' => $covered_area ?: ($area_value . ' ' . $area_unit),
                'bedrooms' => $bedrooms,
                'bathrooms' => $bathrooms,
                'status' => $status,
                'featured' => $featured,
                'image' => $image_path,
                'gallery' => $gallery,
                'description' => $description,
                'amenities' => is_array($amenities_input) ? array_values($amenities_input) : [],
                'created_at' => date('Y-m-d H:i:s')
            ];

            array_unshift($props, $new_property);
            save_properties($props);
            set_flash('success', "Property {$new_id} added successfully to listings.");
        }

        header('Location: properties.php');
        exit;
    }
}

// Available Pakistani Cities & Amenities
$cities = ['Lahore', 'Islamabad', 'Karachi', 'Rawalpindi', 'Faisalabad', 'Kasur'];
$categories = ['Houses', 'Apartments', 'Residential Plots', 'Commercial Properties', 'Shops', 'Luxury Villas'];
$common_amenities = [
    '24/7 Security & CCTV',
    'Sui Gas Connection',
    'Underground Electricity',
    '15kW Solar Net Metering',
    'LDA / CDA Verified Title',
    'Park Facing',
    'Corner Plot',
    'Double Height Ceiling',
    'Imported Spanish Tiles',
    'Servant Quarters with Bath',
    'Standby Power Generator',
    'Heated Swimming Pool',
    'Gym & Fitness Area'
];

$selected_amenities = $property['amenities'] ?? [];

include __DIR__ . '/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h3><?php echo $is_edit ? 'Edit Property Information' : 'Enter New Property Specifications'; ?></h3>
    <a href="properties.php" class="btn btn-sm btn-outline">← Back to List</a>
  </div>

  <div style="padding: 30px;">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
          <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="property-form.php<?php echo $is_edit ? '?id=' . urlencode($property['id']) : ''; ?>" enctype="multipart/form-data">
      
      <!-- Row 1: Title & Purpose -->
      <div class="form-grid-2">
        <div class="form-group">
          <label class="form-label" for="propTitle">Property Title *</label>
          <input type="text" id="propTitle" name="title" class="form-control" placeholder="e.g. 1 Kanal Modern Spanish Villa" required value="<?php echo htmlspecialchars($_POST['title'] ?? $property['title'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="propPurpose">Listing Purpose *</label>
          <select id="propPurpose" name="purpose" class="form-control">
            <option value="sale" <?php echo (($_POST['purpose'] ?? $property['purpose'] ?? '') === 'sale') ? 'selected' : ''; ?>>For Sale</option>
            <option value="rent" <?php echo (($_POST['purpose'] ?? $property['purpose'] ?? '') === 'rent') ? 'selected' : ''; ?>>For Rent</option>
          </select>
        </div>
      </div>

      <!-- Row 2: Category, City, Status -->
      <div class="form-grid-3">
        <div class="form-group">
          <label class="form-label" for="propCategory">Property Category *</label>
          <select id="propCategory" name="category" class="form-control">
            <?php foreach ($categories as $cat): ?>
              <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo (($_POST['category'] ?? $property['category'] ?? '') === $cat) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="propCity">City *</label>
          <select id="propCity" name="city" class="form-control">
            <?php foreach ($cities as $city): ?>
              <option value="<?php echo htmlspecialchars($city); ?>" <?php echo (($_POST['city'] ?? $property['city'] ?? '') === $city) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($city); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="propStatus">Property Status *</label>
          <select id="propStatus" name="status" class="form-control">
            <option value="Available" <?php echo (($_POST['status'] ?? $property['status'] ?? '') === 'Available') ? 'selected' : ''; ?>>Available</option>
            <option value="Under Offer" <?php echo (($_POST['status'] ?? $property['status'] ?? '') === 'Under Offer') ? 'selected' : ''; ?>>Under Offer</option>
            <option value="Sold" <?php echo (($_POST['status'] ?? $property['status'] ?? '') === 'Sold') ? 'selected' : ''; ?>>Sold</option>
            <option value="Rented" <?php echo (($_POST['status'] ?? $property['status'] ?? '') === 'Rented') ? 'selected' : ''; ?>>Rented</option>
          </select>
        </div>
      </div>

      <!-- Row 3: Complete Address / Location -->
      <div class="form-group">
        <label class="form-label" for="propLocation">Location / Society / Sector Address *</label>
        <input type="text" id="propLocation" name="location" class="form-control" placeholder="e.g. DHA Phase 6, Sector J, Street 14, Lahore" required value="<?php echo htmlspecialchars($_POST['location'] ?? $property['location'] ?? ''); ?>">
      </div>

      <!-- Row 4: Price, Area, Unit -->
      <div class="form-grid-3">
        <div class="form-group">
          <label class="form-label" for="propPrice">Price in Pakistani Rupees (PKR) *</label>
          <input type="number" id="propPrice" name="price" class="form-control" placeholder="e.g. 85000000 for 8.5 Crore" required value="<?php echo htmlspecialchars($_POST['price'] ?? $property['price'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="propAreaVal">Size Value *</label>
          <input type="number" step="0.1" id="propAreaVal" name="area_value" class="form-control" placeholder="e.g. 1, 5, 10" required value="<?php echo htmlspecialchars($_POST['area_value'] ?? $property['area_value'] ?? '1'); ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="propAreaUnit">Size Unit *</label>
          <select id="propAreaUnit" name="area_unit" class="form-control">
            <option value="Marla" <?php echo (($_POST['area_unit'] ?? $property['area_unit'] ?? '') === 'Marla') ? 'selected' : ''; ?>>Marla</option>
            <option value="Kanal" <?php echo (($_POST['area_unit'] ?? $property['area_unit'] ?? '') === 'Kanal') ? 'selected' : ''; ?>>Kanal</option>
            <option value="Sq. Ft" <?php echo (($_POST['area_unit'] ?? $property['area_unit'] ?? '') === 'Sq. Ft') ? 'selected' : ''; ?>>Sq. Ft</option>
            <option value="Sq. Yds" <?php echo (($_POST['area_unit'] ?? $property['area_unit'] ?? '') === 'Sq. Yds') ? 'selected' : ''; ?>>Sq. Yds</option>
          </select>
        </div>
      </div>

      <!-- Row 5: Covered Area, Beds, Baths -->
      <div class="form-grid-3">
        <div class="form-group">
          <label class="form-label" for="propCoveredArea">Covered Area (Text)</label>
          <input type="text" id="propCoveredArea" name="covered_area" class="form-control" placeholder="e.g. 4,500 Sq. Ft" value="<?php echo htmlspecialchars($_POST['covered_area'] ?? $property['covered_area'] ?? ''); ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="propBedrooms">Bedrooms (0 for plots/shops)</label>
          <input type="number" id="propBedrooms" name="bedrooms" class="form-control" min="0" max="20" value="<?php echo htmlspecialchars($_POST['bedrooms'] ?? $property['bedrooms'] ?? '0'); ?>">
        </div>

        <div class="form-group">
          <label class="form-label" for="propBathrooms">Bathrooms</label>
          <input type="number" id="propBathrooms" name="bathrooms" class="form-control" min="0" max="20" value="<?php echo htmlspecialchars($_POST['bathrooms'] ?? $property['bathrooms'] ?? '0'); ?>">
        </div>
      </div>

      <!-- Row 6: Image Upload -->
      <div class="form-group">
        <label class="form-label" for="propertyImageInput">Upload Property Image (JPG, PNG, WebP up to 5MB)</label>
        <input type="file" id="propertyImageInput" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
        
        <div class="preview-box">
          <?php if (!empty($property['image'])): ?>
            <img id="imagePreview" src="../<?php echo htmlspecialchars($property['image']); ?>" alt="Current Property Photo">
          <?php else: ?>
            <span id="previewPlaceholder" style="color:var(--adm-grey);font-size:0.88rem;">Selected image preview will appear here</span>
            <img id="imagePreview" src="" alt="Preview" style="display:none;">
          <?php endif; ?>
        </div>
      </div>

      <!-- Row 7: Description -->
      <div class="form-group">
        <label class="form-label" for="propDesc">Full Property Description *</label>
        <textarea id="propDesc" name="description" rows="5" class="form-control" placeholder="Detailed architectural highlights, fittings, society amenities..." required><?php echo htmlspecialchars($_POST['description'] ?? $property['description'] ?? ''); ?></textarea>
      </div>

      <!-- Row 8: Key Amenities Checkboxes -->
      <div class="form-group">
        <label class="form-label">Key Amenities & Society Features</label>
        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:10px;background:#FAF7F2;padding:16px;border-radius:6px;border:1px solid var(--adm-border);">
          <?php foreach ($common_amenities as $amenity): ?>
            <label style="display:inline-flex;align-items:center;gap:6px;font-size:0.85rem;cursor:pointer;">
              <input type="checkbox" name="amenities[]" value="<?php echo htmlspecialchars($amenity); ?>" <?php echo in_array($amenity, $selected_amenities) ? 'checked' : ''; ?> style="accent-color:var(--adm-burgundy);">
              <span><?php echo htmlspecialchars($amenity); ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Featured Listing Checkbox -->
      <div class="form-group" style="margin-bottom:30px;">
        <label style="display:inline-flex;align-items:center;gap:8px;font-size:0.92rem;font-weight:600;cursor:pointer;">
          <input type="checkbox" name="featured" value="1" <?php echo (!empty($_POST['featured']) || !empty($property['featured'])) ? 'checked' : ''; ?> style="accent-color:var(--adm-burgundy);width:18px;height:18px;">
          <span>Show in Homepage Featured Listings</span>
        </label>
      </div>

      <!-- Form Submit Buttons -->
      <div style="display:flex;gap:12px;">
        <button type="submit" class="btn btn-burgundy" style="padding:12px 28px;">
          <?php echo $is_edit ? 'Save Property Updates' : 'Publish Property Listing'; ?>
        </button>
        <a href="properties.php" class="btn btn-outline">Cancel</a>
      </div>

    </form>
  </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>

