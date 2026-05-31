function checkCheckoutRequest() {
  return fetch('/api/serviceHandler.php?module=checkout&action=checkCheckout', {
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

function checkVoucherRequest(voucherCode) {
  return fetch('/api/serviceHandler.php?module=checkout&action=checkVoucher', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ voucher_code: voucherCode })
  }).then(res => res.json());
}