# Abdullah Property Dealer (APD)
### Premier Pakistani Real Estate Property Management Platform

A premium, fully functional, and professional real estate property management web platform tailored specifically for the Pakistani real estate market.

---

## 🌟 Technologies Used
- **Frontend**: HTML5, CSS3, Vanilla JavaScript (Zero external JS frameworks; no React, Next.js, or Vue)
- **Backend**: Core PHP 8+ (Zero backend frameworks; no Laravel, Symfony, or CodeIgniter)
- **Data Persistence**: JSON Demo Storage (`data/properties.json`, `data/inquiries.json`, `data/admin.json`)
- **Typography**: Google Fonts (*Cinzel* serif for luxury headings, *Plus Jakarta Sans* for modern body text)

---

## 🎨 Color Palette & Luxury Aesthetics
Strictly adheres to the specified luxury Pakistani real estate palette:
- **Warm Cream** (`#FAF7F2`, `#F5EFE6`, `#EDE5D8`)
- **Pure White** (`#FFFFFF`)
- **Burgundy** (`#6B1724`, `#4D0E18`, `#8A2234`)
- **Charcoal Black** (`#171717`, `#222222`, `#333333`)
- **Terracotta** (`#C25732`, `#A14220`, `#D96E48`)
- **Warm Grey** (`#57504A`, `#857D76`, `#D7D0C6`, `#E5DFD6`)
- **Strictly Avoided**: Blue, Green, Cyan, Teal, Turquoise. *(All buttons, icons, and WhatsApp elements are custom-styled in Burgundy, Terracotta, or Charcoal to honor this rule).*

---

## 🇵🇰 Pakistani Real Estate Market Integration
- **Cities Covered**: Lahore, Islamabad, Karachi, Rawalpindi, Faisalabad, Kasur.
- **Societies & Hotspots**: DHA Phase 6 Lahore, Bahria Town Islamabad, Clifton Karachi, Chaklala Scheme 3 Rawalpindi, D Ground Faisalabad, Ferozepur Road Kasur.
- **Land Measurement Units**: Marla (e.g. 5 Marla, 10 Marla), Kanal (e.g. 1 Kanal, 2 Kanal), and Square Feet.
- **Price Format**: Pakistani Rupees formatted in Crores (e.g. `PKR 8.5 Crore`) and Lakhs (e.g. `PKR 45 Lakh`, `PKR 1.8 Lakh / Month`).
- **Country Code**: `+92` pre-filled on all inquiry and contact forms.
- **Demo Data Notice**: Sample disclaimer prominently displayed across topbar, listings, and footer.

---

## 📂 Project Structure
```text
abdullah-property-dealer/
├── admin/
│   ├── admin-header.php      # Reusable admin sidebar, navbar & flash alerts
│   ├── admin-footer.php      # Admin footer & scripts
│   ├── index.php             # Admin Dashboard with real-time statistics
│   ├── login.php             # Secure admin login with password hashing
│   ├── logout.php            # Session termination
│   ├── properties.php        # Manage listings table with quick status update
│   ├── property-form.php     # Add & edit property form with image upload
│   ├── delete-property.php   # CSRF-protected property deletion
│   └── inquiries.php         # Customer inquiries table with WhatsApp follow-up
├── api/
│   ├── contact.php           # Validates and saves inquiries to JSON
│   └── properties.php        # JSON API endpoint for dynamic filtering
├── assets/
│   ├── css/
│   │   ├── style.css         # Public website luxury styles & responsive design
│   │   └── admin.css         # Admin panel styles
│   ├── js/
│   │   ├── main.js           # Live client-side filters, gallery switcher, AJAX forms
│   │   └── admin.js          # Admin file upload previews & confirmations
│   └── images/               # Curated high-res Pakistani architecture photos
├── data/
│   ├── admin.json            # Hashed admin credentials
│   ├── inquiries.json        # Stored customer inquiries
│   └── properties.json       # Pakistani property database
├── includes/
│   ├── config.php            # Application constants, helpers, and data handlers
│   ├── header.php            # Public navbar with APD logo & Contact Agent CTA
│   └── footer.php            # Public footer, disclaimer, and WhatsApp floating CTA
├── uploads/                  # Safe uploaded property photos
├── index.php                 # Homepage (Hero search, categories, featured, about, contact)
├── properties.php            # Full property directory with advanced JS filtering
├── property-details.php      # Dedicated property page with gallery & specs
└── README.md                 # Documentation & setup guide
```

---

## 🚀 How to Run the Website

### Option 1: Using Built-in PHP Server (Fastest)
1. Open terminal / command prompt.
2. Navigate to the project directory:
   ```bash
   cd "c:\Users\New Jawad Computers\Abdullah Property Dealer\abdullah-property-dealer"
   ```
3. Start the PHP built-in server:
   ```bash
   "C:\xampp\php\php.exe" -S localhost:8000
   ```
   *(Or just `php -S localhost:8000` if PHP is in your system PATH).*
4. Open your browser and visit:
   - **Public Website**: [http://localhost:8000](http://localhost:8000)
   - **Admin Portal**: [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)

### Option 2: Using XAMPP Apache
1. Copy or move `abdullah-property-dealer` into your XAMPP `htdocs` folder:
   `C:\xampp\htdocs\abdullah-property-dealer`
2. Start Apache from the XAMPP Control Panel.
3. Open browser and visit:
   `http://localhost/abdullah-property-dealer`

---

## 🔐 Admin Portal Credentials
- **Login URL**: `/admin/login.php`
- **Username**: `admin`
- **Password**: `admin123`
*(Passwords are stored as bcrypt hashes in `data/admin.json` via PHP `password_hash()` and verified via `password_verify()`.)*

---

## 📋 Features Checklist
- [x] **Branding & Logo**: APD initials badge with luxury typography.
- [x] **Navbar**: Home, Buy Property, Rent Property, Commercial, About Us, Contact + "Contact Agent" CTA.
- [x] **Hero Section**: Full-width background image with Buy/Rent switch, City, Type, Min/Max price filters.
- [x] **Category Cards**: Houses, Apartments, Plots, Commercial, Shops, Villas (with clickable filter action).
- [x] **Featured Listings**: Realistic Pakistani data (Lahore, Islamabad, Karachi, Rawalpindi, Faisalabad, Kasur).
- [x] **Dedicated Details Page**: Multi-image interactive gallery, description, key specs, and inquiry form.
- [x] **Dynamic Vanilla JS Filters**: Instant filtering by City, Purpose, Category, Bedrooms, Price without page reload.
- [x] **Admin Dashboard**: Real-time counters (Total, Available, Sold, Rented, Inquiries).
- [x] **Admin Operations**: Add, Edit, Delete, Update Status, Upload Images (JPG/PNG/WebP <= 5MB).
- [x] **Contact Section**: Name, Address, Phone (+92 prefilled), Message with PHP JSON persistence.
- [x] **WhatsApp Integration**: Direct click-to-chat links with configurable numbers and pre-filled inquiry text.
- [x] **Color Palette Compliance**: 100% compliant; zero blue, green, cyan, teal, or turquoise.

