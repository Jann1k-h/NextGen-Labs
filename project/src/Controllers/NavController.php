<?php

class NavController
{
    public function reloadUserArea(): void
    {
        // Einbinden der Nav-User-Area
        include VIEWS_PATH . '/shared/nav_user_area.php';
    }
}