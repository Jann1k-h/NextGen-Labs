$(document).on('click', '#login-button-nav', function() {
    $('#login-modal').show();
});

$(document).on('click', '.register-button-modal', function() {
    $('#login-modal').hide();
    $('#register-modal').show();
});

$(document).on('click', '.login-button-modal', function() {
    $('#register-modal').hide();
    $('#forgot-password-modal').hide();
    $('#login-modal').show();
});

$(document).on('click', '.forgot-password-button-modal', function() {
    $('#login-modal').hide();
    $('#register-modal').hide();
    $('#forgot-password-modal').show();
});

$(document).on('click', '.close-modal', function() {
    $(this).parent().hide();
});

$(document).on('click', '#login-submit-button-modal', function() {
    const username = $('#login-username').val().trim();
    const password = $('#login-password').val().trim();

    let valid = true;

    $('#login-username-error').text('');
    $('#login-password-error').text('');

    if (username === '') {
        $('#login-username-error').text('Bitte Username eingeben');
        valid = false;
    }

    if (password === '') {
        $('#login-password-error').text('Bitte Passwort eingeben');
        valid = false;
    }

    if (!valid) {
        return;
    }

    console.log("Username: " + username + ", Password: " + password); // Debug-Ausgabe
    
});

$(document).on('click', '#register-submit-button-modal', function() {
    const username = $('#register-username').val().trim();
    const firstname = $('#register-firstname').val().trim();
    const lastname = $('#register-lastname').val().trim();
    const address = $('#register-address').val().trim();
    const zipcode = $('#register-zipcode').val().trim();
    const city = $('#register-city').val().trim();
    const email = $('#register-email').val().trim();
    const password = $('#register-password').val().trim();
    const confirmPassword = $('#register-password-confirm').val().trim();

    let valid = true;

    $('#register-username-error').text('');
    $('#register-firstname-error').text('');
    $('#register-lastname-error').text('');
    $('#register-address-error').text('');
    $('#register-zipcode-error').text('');
    $('#register-city-error').text('');
    $('#register-email-error').text('');
    $('#register-password-error').text('');
    $('#register-confirm-password-error').text('');

    if (username === '') {
        $('#register-username-error').text('Bitte Username eingeben');
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

    if (!valid) {
        return;
    }

    console.log("Username: " + username + ", Firstname: " + firstname + ", Lastname: " + lastname + ", Address: " + address + ", Zipcode: " + zipcode + ", City: " + city + ", Email: " + email); // Debug-Ausgabe
});

function isValidEmail(email) {
    // Einfache Regex (= Muster) zur Überprüfung der Email-Adresse
    // Ein Text, der aus einem Teil ohne Leerzeichen/@ besteht, gefolgt von @, dann wieder Text ohne Leerzeichen/@, dann ein Punkt und danach erneut Text ohne Leerzeichen/@ (also Format „text@text.text)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

$(document).on('click', '#logout-button-nav', function() {

    fetch('/api/auth/logout.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            $('#user-area').html(`
                <p>Du bist nicht eingeloggt.</p>
                <button id="login-button-nav">Login</button>
            `);
        }
    });

});