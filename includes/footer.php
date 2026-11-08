<?php
/**
 * Abdullah Property Dealer - Reusable Footer Component
 */
require_once __DIR__ . '/config.php';
?>
  <!-- Footer -->
  <footer class="footer">
    <div class="footer-top">
      <div class="container">
        
        <!-- Demo Disclaimer Banner in Footer -->
        <div class="footer-disclaimer-card">
          <strong>Sample & Demo Data Notice:</strong> All property data, imagery, price valuations, and inquiries displayed on this platform are for technical demonstration and sample presentation purposes. Do not represent real agency legal listings.
        </div>

        <div class="footer-grid">
          <!-- Column 1: Brand Info -->
          <div class="footer-about">
            <div class="brand-logo" style="margin-bottom: 12px;">
              <div class="logo-badge" style="border-color: var(--c-terracotta);">APD</div>
              <div class="brand-text">
                <span class="brand-name" style="color:#FFFFFF;">Abdullah Property Dealer</span>
                <span class="brand-sub" style="color:var(--c-terracotta-light);">Pakistan Real Estate</span>
              </div>
            </div>
            <p>
              Your trusted partner for residential, luxury villas, commercial developments, and investment plots across major cities in Pakistan. Professional consultancy, verified documentation, and transparent transactions.
            </p>
            <div style="display:flex;gap:10px;align-items:center;">
              <a href="https://wa.me/<?php echo AGENT_WHATSAPP; ?>?text=Assalam-o-Alaikum%20Abdullah%20Property%20Dealer,%20I%20would%20like%20to%20inquire%20about%20properties." target="_blank" class="btn btn-sm btn-terracotta" style="padding:6px 14px;" rel="noopener noreferrer">
                WhatsApp Agent
              </a>
              <a href="admin/login.php" class="btn btn-sm btn-outline" style="color:#FFFFFF;border-color:rgba(255,255,255,0.25);">
                Admin Login
              </a>
            </div>
          </div>

          <!-- Column 2: Quick Links -->
          <div class="footer-col">
            <h4 class="footer-title">Explore Properties</h4>
            <ul class="footer-links">
              <li><a href="properties.php?purpose=sale">Buy House or Villa</a></li>
              <li><a href="properties.php?purpose=rent">Rental Properties</a></li>
              <li><a href="properties.php?type=Residential+Plots">Residential Plots</a></li>
              <li><a href="properties.php?type=Commercial+Properties">Commercial Plazas</a></li>
              <li><a href="properties.php?type=Shops">Commercial Shops</a></li>
              <li><a href="properties.php?type=Luxury+Villas">Luxury Spanish Villas</a></li>
            </ul>
          </div>

          <!-- Column 3: Pakistani Coverage -->
          <div class="footer-col">
            <h4 class="footer-title">Key Cities Covered</h4>
            <ul class="footer-links">
              <li><a href="properties.php?city=Lahore">Lahore (DHA, Gulberg, Bahria)</a></li>
              <li><a href="properties.php?city=Islamabad">Islamabad (F-7, DHA, Bahria)</a></li>
              <li><a href="properties.php?city=Karachi">Karachi (Clifton, DHA Phase 8)</a></li>
              <li><a href="properties.php?city=Rawalpindi">Rawalpindi (Chaklala, Bahria)</a></li>
              <li><a href="properties.php?city=Faisalabad">Faisalabad (Canal Rd, D Ground)</a></li>
              <li><a href="properties.php?city=Kasur">Kasur (Ferozepur Rd Schemes)</a></li>
            </ul>
          </div>

          <!-- Column 4: Contact Info -->
          <div class="footer-col">
            <h4 class="footer-title">Contact Office</h4>
            <ul class="footer-links" style="font-size:0.88rem;color:var(--c-grey-light);">
              <li style="margin-bottom:12px;display:flex;gap:8px;">
                <span style="color:var(--c-terracotta);">📍</span>
                <span><?php echo htmlspecialchars(AGENT_ADDRESS); ?></span>
              </li>
              <li style="margin-bottom:12px;display:flex;gap:8px;">
                <span style="color:var(--c-terracotta);">📞</span>
                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', AGENT_PHONE); ?>" style="color:#FFFFFF;"><?php echo htmlspecialchars(AGENT_PHONE); ?></a>
              </li>
              <li style="margin-bottom:12px;display:flex;gap:8px;">
                <span style="color:var(--c-terracotta);">✉️</span>
                <a href="mailto:<?php echo htmlspecialchars(AGENT_EMAIL); ?>" style="color:#FFFFFF;"><?php echo htmlspecialchars(AGENT_EMAIL); ?></a>
              </li>
              <li style="margin-bottom:12px;display:flex;gap:8px;">
                <span style="color:var(--c-terracotta);">💬</span>
                <a href="https://wa.me/<?php echo AGENT_WHATSAPP; ?>" target="_blank" rel="noopener noreferrer" style="color:#FFFFFF;">WhatsApp: <?php echo htmlspecialchars(AGENT_PHONE); ?></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer-bottom">
      <div class="container">
        <div>
          &copy; <?php echo date('Y'); ?> <strong>Abdullah Property Dealer</strong>. All Rights Reserved. Business Location: Pakistan.
        </div>
        <div style="font-size: 0.78rem;">
          Designed with pure HTML5, CSS3, Vanilla JS & Core PHP.
        </div>
      </div>
    </div>
  </footer>

  <!-- Reusable Floating WhatsApp Button (Styled strictly in Burgundy/Charcoal/Terracotta - No Green!) -->
  <a href="https://wa.me/<?php echo AGENT_WHATSAPP; ?>?text=Assalam-o-Alaikum%20Abdullah%20Property%20Dealer,%20I%20am%20interested%20in%20property%20consultancy." 
     target="_blank" 
     rel="noopener noreferrer"
     class="floating-whatsapp-btn"
     title="Chat with Agent on WhatsApp"
     style="position:fixed;bottom:25px;right:25px;z-index:999;background:var(--c-burgundy);color:#FFFFFF;padding:12px 18px;border-radius:50px;display:inline-flex;align-items:center;gap:10px;box-shadow:0 8px 25px rgba(107,23,36,0.4);border:2px solid var(--c-terracotta);font-weight:700;font-size:0.88rem;transition:all 0.3s ease;">
    <!-- WhatsApp Icon in Monochrome White -->
    <svg style="width:20px;height:20px;fill:currentColor" viewBox="0 0 24 24">
      <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.66 20.16 9.3 19.8 8.1 19.09L7.81 18.92L4.69 19.74L5.52 16.7L5.33 16.4C4.55 15.15 4.13 13.55 4.13 11.92C4.13 7.37 7.84 3.67 12.05 3.67M9.27 7.55C9.07 7.55 8.74 7.62 8.46 7.92C8.18 8.23 7.39 8.97 7.39 10.45C7.39 11.93 8.47 13.36 8.62 13.56C8.77 13.76 10.74 16.8 13.76 18.1C14.48 18.41 15.04 18.6 15.48 18.74C16.2 18.97 16.85 18.94 17.37 18.86C17.95 18.77 19.16 18.12 19.41 17.41C19.67 16.7 19.67 16.09 19.59 15.96C19.51 15.83 19.31 15.76 19.01 15.61C18.71 15.46 17.23 14.73 16.96 14.63C16.68 14.53 16.48 14.48 16.28 14.78C16.08 15.09 15.5 15.76 15.33 15.96C15.15 16.16 14.98 16.18 14.68 16.03C14.38 15.88 13.41 15.56 12.27 14.54C11.38 13.75 10.78 12.77 10.63 12.52C10.48 12.26 10.61 12.13 10.76 11.98C10.9 11.85 11.06 11.63 11.21 11.46C11.36 11.28 11.41 11.16 11.51 10.96C11.61 10.76 11.56 10.58 11.49 10.43C11.41 10.28 10.84 8.88 10.6 8.3C10.37 7.74 10.13 7.82 9.96 7.81C9.8 7.8 9.61 7.8 9.42 7.8L9.27 7.55Z"/>
    </svg>
    <span>WhatsApp Agent</span>
  </a>

  <!-- Main JavaScript File -->
  <script src="assets/js/main.js"></script>
</body>
</html>

