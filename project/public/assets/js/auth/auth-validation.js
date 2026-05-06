// Funktion zum Zurücksetzen der Fehlermeldungen im Login-Formular
function resetLoginErrors() {
    $('#login-identifier-error').text('');
    $('#login-password-error').text('');
}

// Funktion zum Zurücksetzen der Fehlermeldungen im Registrierung-Formular
function resetRegisterErrors() {
    $('#register-title-error').text('');
    $('#register-firstname-error').text('');
    $('#register-lastname-error').text('');
    $('#register-username-error').text('');
    $('#register-address-error').text('');
    $('#register-zipcode-error').text('');
    $('#register-city-error').text('');
    $('#register-email-error').text('');
    $('#register-password-error').text('');
    $('#register-password-confirm-error').text('');
    // $('#register-payment-info-error').text('');
}