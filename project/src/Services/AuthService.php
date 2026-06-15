<?php

// Zentrale Klasse, um die Authentifizierungslogik zu kapseln
class AuthService
{
    private AuthRepository $authRepository;

    // --------------------------------------------------
    // Repository vorbereiten
    public function __construct()
    {
        // Repository erstellen, damit der Service auf Userdaten zugreifen kann
        $this->authRepository = new AuthRepository();
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // User einloggen
    public function login(string $identifier, string $password, bool $rememberMe): array
    {
        // Prüfen, ob E-Mail oder Username eingegeben wurde
        if ($identifier === '') {
            return [
                'success' => false,
                'message' => 'Bitte E-Mail oder Username eingeben'
            ];
        }

        // Prüfen, ob Passwort eingegeben wurde
        if ($password === '') {
            return [
                'success' => false,
                'message' => 'Bitte Passwort eingeben'
            ];
        }

        try {
            // User anhand von E-Mail oder Username suchen
            $user = $this->authRepository->findByIdentifier($identifier);

            // Prüfen, ob der User existiert
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User nicht gefunden'
                ];
            }

            // Prüfen, ob der Account aktiv ist
            if ((int)$user['is_active'] !== 1) {
                return [
                    'success' => false,
                    'message' => 'Account ist deaktiviert'
                ];
            }

            // Passwort mit dem gespeicherten Passwort-Hash vergleichen
            if (!password_verify($password, $user['password'])) {
                return [
                    'success' => false,
                    'message' => 'Falsches Passwort'
                ];
            }

            if ($rememberMe) {
                // Zufälligen Remember-Token erstellen
                $token = bin2hex(random_bytes(16));

                // Ablaufdatum für den Token setzen
                $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));

                // Remember-Token in der Datenbank speichern
                $this->authRepository->updateRememberToken((int)$user['id'], $token, $expires);

                // Remember-Token als Cookie speichern
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), "/");
            } else {
                // Alten Remember-Token aus der Datenbank löschen
                $this->authRepository->updateRememberToken((int)$user['id'], null, null);

                // Alten Remember-Token-Cookie löschen
                setcookie('remember_token', '', time() - 3600, "/");
            }

            // Userdaten in die Session schreiben
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = (int)$user['is_admin'];

            // Gast-Warenkorb mit User-Warenkorb zusammenführen
            $cartService = new CartService();
            $cartService->mergeGuestCartIntoUserCart((int)$user['id']);

            return [
                'success' => true,
                'message' => 'Erfolgreich eingeloggt',
                'username' => $user['username'],
                'is_admin' => (int)$user['is_admin']
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'DB-Fehler'
            ];
        }
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // User registrieren
    public function register(
        string $title,
        string $firstname,
        string $lastname,
        string $username,
        string $address,
        string $zipcode,
        string $city,
        string $email,
        string $password,
        string $confirmPassword,
        string $paymentInfo
    ): array {
        // Prüfen, ob alle Pflichtfelder ausgefüllt wurden
        if ($title === '') {
            return ['success' => false, 'message' => 'Bitte Anrede auswählen'];
        }

        if ($firstname === '') {
            return ['success' => false, 'message' => 'Bitte Vorname eingeben'];
        }

        if ($lastname === '') {
            return ['success' => false, 'message' => 'Bitte Nachname eingeben'];
        }

        if ($username === '') {
            return ['success' => false, 'message' => 'Bitte Username eingeben'];
        }

        if ($address === '') {
            return ['success' => false, 'message' => 'Bitte Adresse eingeben'];
        }

        if ($zipcode === '') {
            return ['success' => false, 'message' => 'Bitte Postleitzahl eingeben'];
        }

        if ($city === '') {
            return ['success' => false, 'message' => 'Bitte Stadt eingeben'];
        }

        if ($email === '') {
            return ['success' => false, 'message' => 'Bitte E-Mail eingeben'];
        }

        if ($password === '') {
            return ['success' => false, 'message' => 'Bitte Passwort eingeben'];
        }

        if ($confirmPassword === '') {
            return ['success' => false, 'message' => 'Bitte Passwort bestätigen'];
        }

        // Prüfen, ob beide Passwörter gleich sind
        if ($password !== $confirmPassword) {
            return [
                'success' => false,
                'message' => 'Passwörter stimmen nicht überein'
            ];
        }

        try {
            // Prüfen, ob E-Mail oder Username bereits existieren
            $existingUserEmail = $this->authRepository->findByIdentifier($email);
            $existingUserUsername = $this->authRepository->findByIdentifier($username);

            if ($existingUserEmail && $existingUserUsername) {
                return [
                    'success' => false,
                    'message' => 'E-Mail und Username sind bereits vergeben'
                ];
            }

            if ($existingUserEmail) {
                return [
                    'success' => false,
                    'message' => 'E-Mail ist bereits vergeben'
                ];
            }

            if ($existingUserUsername) {
                return [
                    'success' => false,
                    'message' => 'Username ist bereits vergeben'
                ];
            }

            // Passwort sicher hashen
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // User in der Datenbank erstellen
            $this->authRepository->createUser(
                $title,
                $firstname,
                $lastname,
                $username,
                $address,
                $zipcode,
                $city,
                $email,
                $hashedPassword,
                $paymentInfo
            );

            return [
                'success' => true,
                'message' => 'Erfolgreich registriert'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'DB-Fehler'
            ];
        }
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // User ausloggen
    public function logout(): array
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            // Remember-Token aus der Datenbank löschen
            $this->authRepository->updateRememberToken($userId, null, null);
        }

        // Alle Session-Daten löschen
        session_unset();
        session_destroy();

        // Remember-Token-Cookie löschen
        setcookie('remember_token', '', time() - 3600, "/");

        return [
            'success' => true,
            'message' => 'Erfolgreich ausgeloggt'
        ];
    }
    // --------------------------------------------------
}