// Anzeigen, Modals, Alerts, Reload

// Button rechts oben in der Navigation zum Öffnen des Login-Modals
$(document).on('click', '#login-button-nav', function() {
    resetLoginErrors();
    $("#login-modal").modal("show");
});

// Button im Login-Modal zum Öffnen des Registrierungs-Modals
$(document).on('click', '.register-button-modal', function() {
    resetRegisterErrors();

    $("#login-modal").modal("hide");
    $("#register-modal").modal("show");
});

// Button im Registrierungs-Modal zum Öffnen des Login-Modals
$(document).on('click', '.login-button-modal', function() {
    resetLoginErrors();
    resetRegisterErrors();
    $("#register-modal").modal("hide");
    $("#login-modal").modal("show");
});

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

// Funktion zum Neuladen des User-Bereichs in der Navigation (z.B. nach Login oder Logout)
function reloadUserArea() {
    $('#user-area').load('/includes/partials/nav_user_area.php');
}