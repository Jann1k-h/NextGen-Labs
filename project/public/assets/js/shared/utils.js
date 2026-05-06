// Funktion zur Überprüfung der Gültigkeit einer Email-Adresse
function isValidEmail(email) {
    // Einfache Regex (= Muster) zur Überprüfung der Email-Adresse
    // Ein Text, der aus einem Teil ohne Leerzeichen/@ besteht, gefolgt von @, dann wieder Text ohne Leerzeichen/@, dann ein Punkt und danach erneut Text ohne Leerzeichen/@ (also Format „text@text.text)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}