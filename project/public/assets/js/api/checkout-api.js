function checkCheckoutRequest() {
  return fetch('/api/serviceHandler.php?module=checkout&action=check', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  }).then(res => res.json());
}

function getCheckoutDataRequest() {
  return fetch('/api/serviceHandler.php?module=checkout&action=getData', {
    method: 'GET'
  }).then(res => res.json());
}