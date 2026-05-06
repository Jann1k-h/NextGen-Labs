<?php

class NavController
{
    public function reloadUserArea(): void
    {
        // Einbinden der Nav-User-Area
        include VIEWS_PATH . '/partials/nav_user_area.php';
    }
}