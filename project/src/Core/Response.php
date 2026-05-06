<?php

// zentrale Klasse, um JSON-Responses zu erstellen

class Response
{
    // durch static wird aus Funktion kein Objekt erstellt, da man keinen Zustand speichern muss, aufruf mit Response::json() möglich
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}