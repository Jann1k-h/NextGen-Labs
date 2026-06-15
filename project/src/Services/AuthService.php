<?php

// zentrale Klasse, um die Authentifizierungslogik zu kapseln

class AuthService
{
    private AuthRepository $userRepository;

    // Funktion wird automatisch aufgerufen, sobald ein Objekt der Klasse erstellt wird, wie zb in Zeile 68
    public function __construct()
    {
        $this->userRepository = new AuthRepository();
    }

    public function login(string $identifier, string $password, bool $rememberMe): array
    {
        if ($identifier === '') {
            return [
                'success' => false,
                'message' => 'Bitte E-Mail oder Username eingeben'
            ];
        }

        if ($password === '') {
            return [
                'success' => false,
                'message' => 'Bitte Passwort eingeben'
            ];
        }

        try {
            $user = $this->userRepository->findByIdentifier($identifier);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User nicht gefunden'
                ];
            }

            if ((int)$user['is_active'] !== 1) {
                return [
                    'success' => false,
                    'message' => 'Account ist deaktiviert'
                ];
            }

            // password_verify() vergleicht das eingegebene Passwort mit dem in der DB gespeicherten Hasht Passwort
            if (!password_verify($password, $user['password'])) {
                return [
                    'success' => false,
                    'message' => 'Falsches Passwort'
                ];
            }

            if ($rememberMe) {
                // Generiere einen zufälligen Token
                $token = bin2hex(random_bytes(16));
                $expires = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 Tage

                // Speichere den Token in der DB
                $this->userRepository->updateRememberToken((int)$user['id'], $token, $expires);

                // Setze den Token als Cookie
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), "/");
            } else {
                // Wenn "Remember Me" nicht ausgewählt ist, lösche den Token aus der DB und dem Cookie
                $this->userRepository->updateRememberToken((int)$user['id'], null, null);

                setcookie('remember_token', '', time() - 3600, "/");
            }

            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = (int)$user['is_admin'];

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

    public function register(string $title, string $firstname, string $lastname, string $username, string $address, string $zipcode, string $city, string $email, string $password, string $confirmPassword, string $paymentInfo): array
    {
        if($title === '') {
            return [
                'success' => false,
                'message' => 'Bitte Anrede auswählen'
            ];
        }

        if($firstname === '') {
            return [
                'success' => false,
                'message' => 'Bitte Vorname eingeben'
            ];
        }

        if($lastname === '') {
            return [
                'success' => false,
                'message' => 'Bitte Nachname eingeben'
            ];
        }

        if($username === '') {
            return [
                'success' => false,
                'message' => 'Bitte Username eingeben'
            ];
        }

        if($address === '') {
            return [
                'success' => false,
                'message' => 'Bitte Adresse eingeben'
            ];
        }

        if($zipcode === '') {
            return [
                'success' => false,
                'message' => 'Bitte Postleitzahl eingeben'
            ];
        }

        if($city === '') {
            return [
                'success' => false,
                'message' => 'Bitte Stadt eingeben'
            ];
        }

        if($email === '') {
            return [
                'success' => false,
                'message' => 'Bitte E-Mail eingeben'
            ];
        }

        if($password === '') {
            return [
                'success' => false,
                'message' => 'Bitte Passwort eingeben'
            ];
        }

        if($confirmPassword === '') {
            return [
                'success' => false,
                'message' => 'Bitte Passwort bestätigen'
            ];
        }

        if($password !== $confirmPassword) {
            return [
                'success' => false,
                'message' => 'Passwörter stimmen nicht überein'
            ];
        }

        try {
            $existingUserEmail = $this->userRepository->findByIdentifier($email);
            $existingUserUsername = $this->userRepository->findByIdentifier($username);

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

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $this->userRepository->createUser($title, $firstname, $lastname, $username, $address, $zipcode, $city, $email, $hashedPassword, $paymentInfo);

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

    public function logout(): array
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            // Lösche den Remember-Token aus der DB
            $this->userRepository->updateRememberToken($userId, null, null);
        }

        // Lösche alle Session-Daten
        session_unset();
        session_destroy();

        // Lösche den Remember-Token-Cookie
        setcookie('remember_token', '', time() - 3600, "/");

        return [
            'success' => true,
            'message' => 'Erfolgreich ausgeloggt'
        ];
    }
}