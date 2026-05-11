function loginRequest(identifier, password, rememberMe) {
    return fetch('/api/serviceHandler.php?module=auth&action=login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            identifier,
            password,
            rememberMe
        })
    
    // fetch in auth-api bekommt die Antwort vom Server
    }).then(async res => {
        const text = await res.text();
        return JSON.parse(text);
    });
}

function registerRequest(title, firstname, lastname, username, address, zipcode, city, email, password, confirmPassword, paymentInfo) {
    return fetch('/api/serviceHandler.php?module=auth&action=register', {
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
    }).then(async res => {
        const text = await res.text();
        return JSON.parse(text);
    });
}

function logoutRequest() {
    return fetch('/api/serviceHandler.php?module=auth&action=logout', {
        method: 'POST'
    }).then(async res => {
        const text = await res.text();
        return JSON.parse(text);
    });
}