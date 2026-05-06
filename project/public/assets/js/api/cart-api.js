function addToCartRequest(courseId) {
  return fetch('/api/cart/add_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      course_id: courseId
    })
  })
  .then(res => res.json());
}

function getCartItemsRequest() {
  return fetch('/api/cart/get_cart.php')
    .then(res => res.json());
}