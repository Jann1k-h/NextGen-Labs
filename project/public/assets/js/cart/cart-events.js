$(document).on('click', '.add-to-cart-home-btn', function () {
  const courseId = $(this).data('id');
  addToCart(courseId);
  loadCartItems();
});

$(document).on('click', '#cart-button-nav', function () {
  loadCartItems();

});

// Optional: Beim Laden der Seite die Cart-Items laden
$(document).ready(() => {
  loadCartItems();
});