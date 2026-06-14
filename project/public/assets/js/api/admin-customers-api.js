// --------------------------------------------------
// Admin Customers API Requests
// --------------------------------------------------

// --------------------------------------------------
// Kunden laden
function getAdminCustomersRequest() {
  return fetch('/api/serviceHandler.php?module=adminCustomers&action=get')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kunden bearbeiten
function updateAdminCustomerRequest(customerData) {
  return fetch('/api/serviceHandler.php?module=adminCustomers&action=update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(customerData)
  }).then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kunden deaktivieren
function deactivateAdminCustomerRequest(customerId) {
  return fetch('/api/serviceHandler.php?module=adminCustomers&action=deactivate', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: customerId
    })
  }).then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Bestellungen eines Kunden laden
function getAdminCustomerOrdersRequest(customerId) {
  return fetch('/api/serviceHandler.php?module=adminCustomers&action=getOrders&id=' + customerId)
    .then(res => res.json());
}
// --------------------------------------------------