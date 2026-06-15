// --------------------------------------------------
// Auth UI + Validation + Events
// --------------------------------------------------


// --------------------------------------------------
// Login-Fehler zurücksetzen
function resetLoginErrors() {
    $('#login-identifier-error').text('');
    $('#login-password-error').text('');
}
// --------------------------------------------------


// --------------------------------------------------
// Registrierungsfehler zurücksetzen
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
// --------------------------------------------------


// --------------------------------------------------
// Login-Modal öffnen
$(document).on('click', '#login-button-nav', function() {
    resetLoginErrors();
    $("#login-modal").modal("show");
});
// --------------------------------------------------


// --------------------------------------------------
// Wechsel von Login zu Registierung
$(document).on('click', '.register-button-modal', function() {
    resetRegisterErrors();

    $("#login-modal").modal("hide");
    $("#register-modal").modal("show");
});
// --------------------------------------------------


// --------------------------------------------------
// Wechsel von Registierung zu Login
$(document).on('click', '.login-button-modal', function() {
    resetLoginErrors();
    resetRegisterErrors();
    $("#register-modal").modal("hide");
    $("#login-modal").modal("show");
});

// --------------------------------------------------
// Login-Handler
$(document).on('click', '#login-submit-button-modal', function() {
    const identifier = $('#login-identifier').val().trim();
    const password = $('#login-password').val().trim();
    const rememberMe = $('#login-remember-me').is(':checked');

    let valid = true;

    resetLoginErrors();

    if (identifier === '') {
        $('#login-identifier-error').text('Bitte Username oder E-Mail eingeben');
        valid = false;
    }

    if (password === '') {
        $('#login-password-error').text('Bitte Passwort eingeben');
        valid = false;
    }

    if (!valid) {
        return;
    }

    console.log("Identifier: " + identifier + ", Password: " + password);

    loginRequest(identifier, password, rememberMe)
    .then(data => {
        if (data.success == true) {

            showAuthAlert(data.message, 'success');

            // User-Area wird neu geladen; danach Warenkorb neu laden, damit der Zähler nach Login korrekt ist.
            reloadUserArea();
            loadCartItems();

            $("#login-modal").modal("hide");

            console.log(data.message);

            const categoryId = $('#category-select').val();

            // Prüfen, ob Elemente von Kursliste vorhanden sind mit length. Wenn length > 0, dann existieren Elemente, ansonsten nicht.
            // Das ist notwendig, damit die Funktion loadCourses() nicht auf Seiten ohne Kursliste (z.B. Details-Seite) aufgerufen wird, 
            // da sie dort zu Fehlern führen würde, weil die notwendigen Elemente fehlen.
            if ($('#course-list').length) {
                loadCourses(categoryId);
            }

            const courseId = $('#course-details').data('course-id');

            // Prüfen, ob Elemente von Kursdetails vorhanden sind mit length. Wenn length > 0, dann existieren Elemente, ansonsten nicht.
            // Das ist notwendig, damit die Funktion loadCourseDetails() nicht auf Seiten ohne Kursdetails (z.B. Startseite) aufgerufen wird, 
            // da sie dort zu Fehlern führen würde, weil die notwendigen Elemente fehlen.
            if (courseId) {
                loadCourseDetails(courseId);
            }

        } else if (data.success == false) {

            showAuthAlert(data.message, 'danger');

            console.log(data.message);
        }
    })
    .catch(error => {
        showAuthAlert('Serverfehler oder ungültige Antwort', 'danger');
        console.error(error);
    });

});
// --------------------------------------------------

// --------------------------------------------------
// Registrierung-Handler
$(document).on('click', '#register-submit-button-modal', function() {

    const title = $('#register-title').val().trim();
    const firstname = $('#register-firstname').val().trim();
    const lastname = $('#register-lastname').val().trim();
    const username = $('#register-username').val().trim();
    const address = $('#register-address').val().trim();
    const zipcode = $('#register-zipcode').val().trim();
    const city = $('#register-city').val().trim();
    const email = $('#register-email').val().trim();
    const password = $('#register-password').val().trim();
    const confirmPassword = $('#register-password-confirm').val().trim();
    const paymentInfo = $('#register-payment-info').val().trim();

    let valid = true;

    resetRegisterErrors();

    if (title === '') {
        $('#register-title-error').text('Bitte Anrede auswählen');
        valid = false;
    }

    if (firstname === '') {
        $('#register-firstname-error').text('Bitte Vorname eingeben');
        valid = false;
    }

    if (lastname === '') {
        $('#register-lastname-error').text('Bitte Nachname eingeben');
        valid = false;
    }

    if (username === '') {
        $('#register-username-error').text('Bitte Username eingeben');
        valid = false;
    }

    if (address === '') {
        $('#register-address-error').text('Bitte Adresse eingeben');
        valid = false;
    }

    if (zipcode === '') {
        $('#register-zipcode-error').text('Bitte PLZ eingeben');
        valid = false;
    }

    if (city === '') {
        $('#register-city-error').text('Bitte Ort eingeben');
        valid = false;
    }

    if (email === '') {
        $('#register-email-error').text('Bitte Emailadresse eingeben');
        valid = false;
    } else if (!isValidEmail(email)) {
        $('#register-email-error').text('Bitte gültige Emailadresse eingeben');
        valid = false;
    }

    if (password === '') {
        $('#register-password-error').text('Bitte Passwort eingeben');
        valid = false;
    }

    if (confirmPassword === '') {
        $('#register-password-confirm-error').text('Bitte Passwort bestätigen');
        valid = false;
    }

    if (password !== confirmPassword) {
        $('#register-password-confirm-error').text('Passwörter stimmen nicht überein');
        valid = false;
    }

    // if (paymentInfo === '') {
    //     $('#register-payment-info-error').text('Bitte Zahlungsinformationen eingeben');
    //     valid = false;
    // }

    if (!valid) {
        return;
    }

    console.log("Title: " + title + ", Firstname: " + firstname + ", Lastname: " + lastname + ", Username: " + username + ", Address: " + address + ", Zipcode: " + zipcode + ", City: " + city + ", Email: " + email + ", Password: " + password + ", ConfirmPassword: " + confirmPassword + ", PaymentInfo: " + paymentInfo);

    registerRequest(title, firstname, lastname, username, address, zipcode, city, email, password, confirmPassword, paymentInfo)
    .then(data => {
        if (data.success == true) {

            showAuthAlert(data.message, 'success');

            // kein reloadUserArea, da der Nutzer nach der Registrierung nicht automatisch eingeloggt wird, sondern erst nach dem Login-Modal, 
            // damit er direkt mit seinem neuen Account einloggen kann. Daher wird hier nur das Login-Modal geöffnet und die User-Area erst nach dem Login aktualisiert.
            // reloadUserArea();

            $("#register-modal").modal("hide");
            $("#login-modal").modal("show");

            $('#login-identifier').val(data.username);
            $('#login-password').val('');

            console.log(data.message);

        } else if (data.success == false) {

            showAuthAlert(data.message, 'danger');

            console.log(data.message);
        }
    })
    .catch(error => {
        showAuthAlert('Serverfehler oder ungültige Antwort', 'danger');
        console.error(error);
    });
});
// --------------------------------------------------

// --------------------------------------------------
// Logout-Handler
$(document).on('click', '#logout-button-nav', function() {
    console.log('Logout Button geklickt');
    logoutRequest()
    .then(data => {
        if (data.success == true) {

            showAuthAlert(data.message, 'success');

            // User-Area wird neu geladen; danach Warenkorb neu laden, damit der Gast-Warenkorb/Zähler korrekt ist.
            reloadUserArea();
            loadCartItems();

            console.log(data.message);

            const categoryId = $('#category-select').val();

            // Prüfen, ob Elemente von Kursliste vorhanden sind mit length. Wenn length > 0, dann existieren Elemente, ansonsten nicht.
            // Das ist notwendig, damit die Funktion loadCourses() nicht auf Seiten ohne Kursliste (z.B. Details-Seite) aufgerufen wird, 
            // da sie dort zu Fehlern führen würde, weil die notwendigen Elemente fehlen.
            if ($('#course-list').length) {
                loadCourses(categoryId);
            }

            const courseId = $('#course-details').data('course-id');

            // Prüfen, ob Elemente von Kursdetails vorhanden sind mit length. Wenn length > 0, dann existieren Elemente, ansonsten nicht.
            // Das ist notwendig, damit die Funktion loadCourseDetails() nicht auf Seiten ohne Kursdetails (z.B. Startseite) aufgerufen wird, 
            // da sie dort zu Fehlern führen würde, weil die notwendigen Elemente fehlen.
            if (courseId) {
                loadCourseDetails(courseId);
            }

        } else if (data.success == false) {

            showAuthAlert(data.message, 'danger');
        }

    })
    .catch(error => {
        showAuthAlert('Serverfehler oder ungültige Antwort', 'danger');
        console.error(error);
    });
});
// --------------------------------------------------