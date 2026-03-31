<div id="user-area">

    <?php if (!isset($_SESSION['user_id'])): ?>
        <p>Du bist nicht eingeloggt.</p>
        <button id="login-button-nav">Login</button>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Willkommen, <?= $_SESSION['username']; ?>!</p>
        <button id="logout-button-nav">Logout</button>
    <?php endif; ?>

</div>

<!-- Login Modal -->
<div id="login-modal" style="display:none;">

    <button class="close-modal">&times;</button>

    <input type="text" id="login-username" placeholder="Username">
    <div class="error" id="login-username-error"></div>

    <input type="password" id="login-password" placeholder="Passwort">
    <div class="error" id="login-password-error"></div>

    <input type="checkbox" id="login-remember-me"> Remember Me</input>

    <button class="forgot-password-button-modal">Forgot Password?</button>

    <button class="register-button-modal">Don't have an account? Register</button>

    <button id="login-submit-button-modal">Login</button>

</div>

<!-- Register Modal -->
<div id="register-modal" style="display:none;">

    <button class="close-modal">&times;</button>

    <input type="text" id="register-username" placeholder="Username">
    <div class="error" id="register-username-error"></div>

    <input type="text" id="register-firstname" placeholder="Vorname">
    <div class="error" id="register-firstname-error"></div>

    <input type="text" id="register-lastname" placeholder="Nachname">
    <div class="error" id="register-lastname-error"></div>

    <input type="text" id="register-address" placeholder="Adresse">
    <div class="error" id="register-address-error"></div>

    <input type="text" id="register-zipcode" placeholder="PLZ">
    <div class="error" id="register-zipcode-error"></div>

    <input type="text" id="register-city" placeholder="Ort">
    <div class="error" id="register-city-error"></div>

    <input type="email" id="register-email" placeholder="Emailadresse">
    <div class="error" id="register-email-error"></div>

    <input type="password" id="register-password" placeholder="Passwort">
    <div class="error" id="register-password-error"></div>

    <input type="password" id="register-password-confirm" placeholder="Passwort bestätigen">
    <div class="error" id="register-password-confirm-error"></div>

    <button class="forgot-password-button-modal">Forgot Password?</button>

    <button class="login-button-modal">Already have an account? Login</button>

    <button id="register-submit-button-modal">Register</button>

</div>

<!-- Forgot Password Modal -->
<div id="forgot-password-modal" style="display:none;">
    <button class="close-modal">&times;</button>
    <input type="email" id="forgot-password-email" placeholder="Emailadresse">
    <button class="login-button-modal">Already have an account? Login</button>
    <button id="forgot-password-submit-button-modal">Reset Password</button>
</div>