// --------------------------------------------------
// Voucher API Requests
// --------------------------------------------------


// --------------------------------------------------
// Alle Gutscheine abrufen
function getVouchersRequest() {
  return fetch('/api/serviceHandler.php?module=voucher&action=get')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Gutscheine erstellen
function createVoucherRequest(voucherData) {
  return fetch('/api/serviceHandler.php?module=voucher&action=create', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(voucherData)
  }).then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Gutscheine aktualisieren
function updateVoucherRequest(voucherId, voucherData) {
  return fetch('/api/serviceHandler.php?module=voucher&action=update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: voucherId,
      ...voucherData
    })
  }).then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Gutscheine löschen
function deleteVoucherRequest(voucherId) {
  return fetch('/api/serviceHandler.php?module=voucher&action=delete', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: voucherId
    })
  }).then(res => res.json());
}
// --------------------------------------------------