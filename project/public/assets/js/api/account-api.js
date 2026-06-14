// --------------------------------------------------
// Account API Requests
// --------------------------------------------------

// --------------------------------------------------
// Eigene Benutzerdaten laden
function getAccountRequest() {
  return fetch('/api/serviceHandler.php?module=account&action=get')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Eigene Benutzerdaten aktualisieren
function updateAccountRequest(accountData) {
  return fetch('/api/serviceHandler.php?module=account&action=update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(accountData)
  }).then(res => res.json());
}
// --------------------------------------------------