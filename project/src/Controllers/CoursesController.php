<?php

class CoursesController
{
    public function getDetails(): void
    {
        $courseId = Request::get('id');
        if (!$courseId) {
            Response::json([
                'success' => false,
                'message' => 'Keine Kurs-ID angegeben'
            ], 400);
            return;
        }

        // Hier würde normalerweise die Logik stehen, um die Kursdetails aus der Datenbank zu holen
        // Zum Beispiel:
        // $courseRepo = new CourseRepository();
        // $course = $courseRepo->getCourseById($courseId);

        // Dummy-Daten für die Antwort
        $course = [
            'id' => $courseId,
            'title' => 'Beispielkurs',
            'description' => 'Dies ist eine detaillierte Beschreibung des Beispielkurses.',
            'category' => 'Programmierung',
            'is_free' => true
        ];

        Response::json([
            'success' => true,
            'data' => $course
        ]);
    }

    public function getCategories(): void
    {
        // Hier würde normalerweise die Logik stehen, um die Kurskategorien aus der Datenbank zu holen

        // Dummy-Daten für die Antwort
        $categories = [
            ['id' => 1, 'name' => 'Programmierung'],
            ['id' => 2, 'name' => 'Design'],
            ['id' => 3, 'name' => 'Marketing']
        ];

        Response::json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getCourses(): void
    {
        $categoryId = Request::get('category_id', '');
        $onlyFree = Request::get('free', false);

        // Hier würde normalerweise die Logik stehen, um die Kurse basierend auf den Filtern aus der Datenbank zu holen

        // Dummy-Daten für die Antwort
        $courses = [
            [
                'id' => 1,
                'title' => 'Einführung in PHP',
                'category_id' => 1,
                'is_free' => true
            ],
            [
                'id' => 2,
                'title' => 'Fortgeschrittenes JavaScript',
                'category_id' => 1,
                'is_free' => false
            ],
            [
                'id' => 3,
                'title' => 'Grundlagen des Grafikdesigns',
                'category_id' => 2,
                'is_free' => true
            ]
        ];

        Response::json([
            'success' => true,
            'data' => $courses
        ]);
    }
}