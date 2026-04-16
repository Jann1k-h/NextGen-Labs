// prüfen, Fehler zurücksetzen

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

// Funktion zur Überprüfung der Gültigkeit einer Email-Adresse
function isValidEmail(email) {
    // Einfache Regex (= Muster) zur Überprüfung der Email-Adresse
    // Ein Text, der aus einem Teil ohne Leerzeichen/@ besteht, gefolgt von @, dann wieder Text ohne Leerzeichen/@, dann ein Punkt und danach erneut Text ohne Leerzeichen/@ (also Format „text@text.text)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}