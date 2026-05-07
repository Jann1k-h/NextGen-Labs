document.addEventListener("DOMContentLoaded", () => {
    loadCoursesCategories();
});

// AJAX: Kategorien vom Backend holen und ins Dropdown rendern
function loadCoursesCategories() {
    getCoursesCategoriesRequest()
        .then(data => renderCoursesCategories(data));
}

// Eventlistener für Kategorie- und Free-Filter
$("#category-select, #free-courses-checkbox").on("change", loadCoursesFromFilters);
// Eventlistener für Live-Suche
$("#course-search").on("keyup", loadCoursesFromFilters);

// Funktion zum Neuladen der Kurse
function loadCoursesFromFilters() {
    loadCourses(
        $("#category-select").val(),
        $("#free-courses-checkbox").is(":checked"),
        $("#course-search").val()
    );
}