// --------------------------------------------------
// Warenkorb UI + Events
// --------------------------------------------------

function addToCart(courseId) {
  return addToCartRequest(courseId)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}

function loadCartItems() {
  return getCartItemsRequest()
    .then(data => {
      $('#cart-items').empty();

      if (!data.success) {
        showAuthAlert(data.message, 'danger');
        updateCartCounter(0);
        return data;
      }

      updateCartCounter(data.count);

      if (data.items.length === 0) {
        $('#cart-items').html(`
          <p class="text-muted mb-0">Dein Warenkorb ist leer.</p>
        `);
      }

      data.items.forEach(item => {
        $('#cart-items').append(`
          <div class="cart-item border-bottom py-3 d-flex gap-3 align-items-center">
            <img src="${item.image_path ?? ''}" alt="" style="width: 80px; height: 60px; object-fit: cover;" class="rounded">

            <div class="flex-grow-1">
              <h6 class="mb-1">${item.title}</h6>
              <div class="fw-bold">${Number(item.price).toFixed(2)} €</div>
            </div>

            <button class="btn btn-sm btn-outline-danger remove-cart-item-btn" data-id="${item.id}">
              Entfernen
            </button>
          </div>
        `);
      });

      $('#cart-total').text(Number(data.total).toFixed(2) + ' €');

      return data;
    });
}

function updateCartCounter(count) {
  const safeCount = count ?? 0;

  $('#cart-count').text(safeCount);

  if (safeCount <= 0) {
    $('#cart-count').addClass('d-none');
  } else {
    $('#cart-count').removeClass('d-none');
  }
}

function openCartOverlay() {
  bootstrap.Offcanvas
    .getOrCreateInstance('#cartOffcanvas')
    .show();
}

// Add-to-Cart Button
$(document).on('click', '.add-to-cart-btn', function () {
  const courseId = $(this).data('id');

  addToCart(courseId).then(data => {
    if (data.success) {
      loadCartItems().then(() => {
        openCartOverlay();
      });
    }
  });
});

// Warenkorb-Button in der Navigation
$(document).on('click', '#cart-button-nav', function () {
  loadCartItems();
});

// Remove Button im Warenkorb
$(document).on('click', '.remove-cart-item-btn', function () {
  const cartItemId = $(this).data('id');

  removeCartItemRequest(cartItemId)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        loadCartItems();
      } else {
        showAuthAlert(data.message, 'danger');
      }
    });
});

// Button "Zur Kasse"
// Button "Zur Kasse"
$(document).on('click', '#checkout-btn', function () {

  checkCheckoutRequest()
    .then(data => {

      if (data.success) {
        showAuthAlert(data.message, 'success');
        window.location.href = '/checkout.php';
      } else {
        showAuthAlert(data.message, 'danger');
      }
    })
});

// Beim Laden der Seite die Cart-Items laden
$(document).ready(() => {
  loadCartItems();
});