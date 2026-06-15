// --------------------------------------------------
// Cart API Requests
// --------------------------------------------------


// --------------------------------------------------
// Kurs zum Warenkorb hinzufügen
function addToCartRequest(courseId) {
  return fetch('/api/serviceHandler.php?module=cart&action=add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      course_id: courseId
    })
  }).then(res => res.json());
}
// --------------------------------------------------

// --------------------------------------------------
// Warenkorb-Inhalt abrufen
function getCartItemsRequest() {
  return fetch('/api/serviceHandler.php?module=cart&action=get')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Eintrag aus dem Warenkorb entfernen
function removeCartItemRequest(cartItemId) {
  return fetch('/api/serviceHandler.php?module=cart&action=remove', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      cart_item_id: cartItemId
    })
  }).then(res => res.json());
}
// --------------------------------------------------