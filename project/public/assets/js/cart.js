// --------------------------------------------------
// Warenkorb UI + Events
// --------------------------------------------------

// --------------------------------------------------
// Beim Laden der Seite die Cart-Items laden
$(document).ready(() => {
  loadCartItems();
});
// --------------------------------------------------


// --------------------------------------------------
// Warenkorb-Daten laden und UI aktualisieren
function loadCartItems() {
  return getCartItemsRequest()
    .then(data => {
      $('#cart-items').empty();

      updateCartCounter(data.count);
      renderCartItems(data.items);
      updateCartTotal(data.total);

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Warenkorb-Zähler in der Navigation aktualisieren
function updateCartCounter(count) {
  $('#cart-count').text(count);
}
// --------------------------------------------------


// --------------------------------------------------
// Warenkorb Kurse aktualisieren
function renderCartItems(items) {
  if (items.length === 0) {
    $('#cart-items').html(`
      <p class="text-muted mb-0">Dein Warenkorb ist leer.</p>
    `);
    return;
  }

  items.forEach(item => {
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
}
// --------------------------------------------------


// --------------------------------------------------
// Warenkorb-Overlay-Gesamtpreis aktualisieren
function updateCartTotal(total) {
  $('#cart-total').text(Number(total).toFixed(2) + ' €');
}
// --------------------------------------------------


// --------------------------------------------------
// Button Events
// --------------------------------------------------


// --------------------------------------------------
// Add-to-Cart Button bei den Kursen
$(document).on('click', '.add-to-cart-btn', function () {
  const courseId = $(this).data('id');

  addToCart(courseId).then(data => {
    if (data.success) {

      // in der Funktion wird auch Warenkorbanzahl aktualisiert
      loadCartItems().then(() => {
        openCartOverlay();
      });
    }
  });
});
// --------------------------------------------------


// --------------------------------------------------
// Warenkorb-Button in der Navigation
$(document).on('click', '#cart-button-nav', function () {
  loadCartItems();
});
// --------------------------------------------------


// --------------------------------------------------
// Remove Button im Warenkorb
$(document).on('click', '.remove-cart-item-btn', function () {
  const cartItemId = $(this).data('id');

  removeCartItem(cartItemId);
});
// --------------------------------------------------


// --------------------------------------------------
// Button "Zur Kasse"
$(document).on('click', '#checkout-btn', function () {
  goToCheckout();
});
// --------------------------------------------------


// --------------------------------------------------
// Funktion Warenkorb-Overlay öffnen
function openCartOverlay() {
  bootstrap.Offcanvas.getOrCreateInstance('#cartOffcanvas').show();
}
// --------------------------------------------------


// --------------------------------------------------
// Funktion Kurs zum Warenkorb hinzufügen
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
// --------------------------------------------------


// --------------------------------------------------
// Funktion Kurs aus dem Warenkorb entfernen
function removeCartItem(cartItemId) {
  return removeCartItemRequest(cartItemId)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');

        // in der Funktion wird auch Warenkorbanzahl aktualisiert
        loadCartItems();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Funktion zur Kasse gehen
function goToCheckout() {
  return checkCheckoutRequest()
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        window.location.href = '/checkout.php';
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------