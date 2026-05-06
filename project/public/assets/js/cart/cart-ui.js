function addToCart(courseId) {
  addToCartRequest(courseId)
  .then(data => {
    if (data.success) {
      showAuthAlert(data.message, 'success');
    } else {
      showAuthAlert(data.message, 'danger');
    }
  });
}

function loadCartItems() {
  getCartItemsRequest()
    .then(data => {
      $('#cart-items').empty();
      data.forEach(item => {
        $('#cart-items').append(`
          <div class="cart-item">
            <h3>${item.course_id}</h3>
            <p>Quantity: ${item.quantity}</p>
          </div>
        `);
      });
    });
}