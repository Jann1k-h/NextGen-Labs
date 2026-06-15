// --------------------------------------------------
// Account Verwaltung UI + Events
// --------------------------------------------------

let originalAccountData = null;

// --------------------------------------------------
// Beim Laden der Seite Account-Daten laden
$(document).ready(() => {

  // Nur wenn account-form-course-Element vorhanden ist Daten laden, damit kein Alert kommt
  if ($('#account-form').length === 0) {
    return;
  }

  loadAccountData();
});
// --------------------------------------------------


// --------------------------------------------------
// Account-Daten laden
function loadAccountData() {
  return getAccountRequest()
    .then(data => {
      if (data.success) {
        originalAccountData = data.user;
        fillAccountForm(data.user);
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Formular mit Benutzerdaten befüllen
function fillAccountForm(user) {
  $('#account-title').val(user.title ?? '');
  $('#account-firstname').val(user.firstname ?? '');
  $('#account-lastname').val(user.lastname ?? '');
  $('#account-username').val(user.username ?? '');
  $('#account-email').val(user.email ?? '');
  $('#account-address').val(user.address ?? '');
  $('#account-zipcode').val(user.zipcode ?? '');
  $('#account-city').val(user.city ?? '');
  $('#account-payment-info').val(user.payment_info ?? '');
  $('#account-current-password').val('');
}
// --------------------------------------------------


// --------------------------------------------------
// Account Formular absenden
$(document).on('submit', '#account-form', function (e) {
  e.preventDefault();

  const accountData = {
    title: $('#account-title').val(),
    firstname: $('#account-firstname').val().trim(),
    lastname: $('#account-lastname').val().trim(),
    username: $('#account-username').val().trim(),
    email: $('#account-email').val().trim(),
    address: $('#account-address').val().trim(),
    zipcode: $('#account-zipcode').val().trim(),
    city: $('#account-city').val().trim(),
    payment_info: $('#account-payment-info').val().trim(),
    current_password: $('#account-current-password').val()
  };

  updateAccount(accountData);
});
// --------------------------------------------------


// --------------------------------------------------
// Account aktualisieren
function updateAccount(accountData) {
  return updateAccountRequest(accountData)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        $('#account-current-password').val('');
        loadAccountData();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Formular zurücksetzen
$(document).on('click', '#account-reset-btn', function () {
  if (originalAccountData !== null) {
    fillAccountForm(originalAccountData);
  }
});
// --------------------------------------------------