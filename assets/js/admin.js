/**
 * Abdullah Property Dealer - Admin Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
  initImagePreview();
  initDeleteConfirmations();
});

function initImagePreview() {
  const fileInput = document.getElementById('propertyImageInput');
  const previewImg = document.getElementById('imagePreview');
  const previewPlaceholder = document.getElementById('previewPlaceholder');

  if (fileInput && previewImg) {
    fileInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        // Validate client side type & size
        if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
          alert('Please select a valid image file (JPG, PNG, or WebP).');
          this.value = '';
          return;
        }

        if (file.size > 5 * 1024 * 1024) {
          alert('File size exceeds 5MB limit.');
          this.value = '';
          return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
          if (previewPlaceholder) {
            previewPlaceholder.style.display = 'none';
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }
}

function initDeleteConfirmations() {
  const deleteBtns = document.querySelectorAll('.btn-confirm-delete');
  deleteBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      const title = this.getAttribute('data-title') || 'this property';
      if (!confirm(`Are you sure you want to delete ${title}? This action cannot be undone.`)) {
        e.preventDefault();
      }
    });
  });
}

