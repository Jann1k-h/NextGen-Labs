// --------------------------------------------------
// Gemeinsame UI-Hilfsfunktionen: Alerts, Navigation neu laden, kleine Validierungen
// --------------------------------------------------


// --------------------------------------------------
// Anzeigen von Alert-Nachrichten im Zusammenhang mit Authentifizierung, Warenkorb usw.
function showAuthAlert(message, type) {
    $('#auth-alert').html(`
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    setTimeout(() => {
        $('#auth-alert .alert').alert('close');
    }, 2000);
}
// --------------------------------------------------


// --------------------------------------------------
// Funktion zum Neuladen des User-Bereichs in der Navigation (z.B. nach Login oder Logout)
// besser über API damit src/ nicht öffentlich zugänglich bleibt
function reloadUserArea() {
    $('#user-area').load('/api/serviceHandler.php?module=nav&action=reloadUserArea');
}

// Funktion zur Überprüfung der Gültigkeit einer Email-Adresse
function isValidEmail(email) {
    // Einfache Regex (= Muster) zur Überprüfung der Email-Adresse
    // Ein Text, der aus einem Teil ohne Leerzeichen/@ besteht, gefolgt von @, dann wieder Text ohne Leerzeichen/@, 
    // dann ein Punkt und danach erneut Text ohne Leerzeichen/@ (also Format „text@text.text")
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
// --------------------------------------------------