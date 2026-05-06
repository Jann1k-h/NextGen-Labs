// Funktion zum Anzeigen von Alert-Nachrichten im Zusammenhang mit Authentifizierung (z.B. Login, Registrierung, Logout)
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