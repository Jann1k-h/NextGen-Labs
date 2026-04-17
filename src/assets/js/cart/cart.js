$(document).on('click', '.add-to-cart-btn', function () {
  const courseId = $(this).data('id');
  addToCart(courseId);
});

function addToCart(courseId) {
  fetch('/api/cart/add.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ course_id: courseId })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showAuthAlert('Zum Warenkorb hinzugefügt', 'success');
    } else {
      showAuthAlert(data.message, 'danger');
    }
  });
}