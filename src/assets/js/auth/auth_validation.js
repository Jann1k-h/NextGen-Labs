// API-Aufrufe und Submit-Handler

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

    fetch('/api/auth/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            identifier,
            password,
            rememberMe
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success == true) {

            showAuthAlert(data.message, 'success');

            reloadUserArea();
            
            $("#login-modal").modal("hide");
            
            console.log(data.message);

            const categoryId = $('#category-select').val();
            loadCourses(categoryId);

        } else if (data.success == false) {

            showAuthAlert(data.message, 'danger');

            console.log(data.message);
        }
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

    fetch('/api/auth/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title,
            firstname,
            lastname,
            username,
            address,
            zipcode,
            city,
            email,
            password,
            confirmPassword,
            paymentInfo
        })
    })
    .then(res => res.json())
    .then(data => { 
        if (data.success == true) {

            showAuthAlert(data.message, 'success');

            // kein reloadUserArea, da der Nutzer nach der Registrierung nicht automatisch eingeloggt wird, sondern erst nach dem Login-Modal, damit er direkt mit seinem neuen Account einloggen kann. Daher wird hier nur das Login-Modal geöffnet und die User-Area erst nach dem Login aktualisiert.
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
    });
});
// --------------------------------------------------

// --------------------------------------------------
// Logout-Handler
$(document).on('click', '#logout-button-nav', function() {

    fetch('/api/auth/logout.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success == true) {

            showAuthAlert(data.message, 'success');

            reloadUserArea();

            console.log(data.message);

            const categoryId = $('#category-select').val();
            loadCourses(categoryId);

        } else if (data.success == false) {

            showAuthAlert(data.message, 'danger');
        }

    });
});
// --------------------------------------------------