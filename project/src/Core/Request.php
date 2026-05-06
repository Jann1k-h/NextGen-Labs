<?php

// zentrale Klasse, um JSON-Requests zu verarbeiten

class Request
{
    // durch static wird aus Funktion kein Objekt erstellt, da man keinen Zustand speichern muss, aufruf mit Request::json() möglich
    public static function getJson(): array
    {
        // innerhalb eines PHP-Request kann man immer auf die Rohdaten zugreifen, die der Client gesendet hat, indem man die php://input-Stream liest
        $data = json_decode(file_get_contents("php://input"), true);
        return is_array($data) ? $data : [];
    }
}