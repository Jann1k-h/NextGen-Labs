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