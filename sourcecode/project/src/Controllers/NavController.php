<?php

class NavController
{
    // --------------------------------------------------
    // User-Bereich in der Navigation neu laden
    // Lädt nur den User-Bereich der Navigation neu, damit sich Login/Logout/Admin-Anzeige ändern kann, ohne die komplette Seite neu zu laden.
    public function reloadUserArea(): void
    {
        // View für den User-Bereich der Navigation einbinden
        include VIEWS_PATH . '/shared/nav_user_area.php';
    }
    // --------------------------------------------------
}