document.addEventListener("DOMContentLoaded", () => {
    loadCourses();
});

// Funktion zum Laden der Kurse vom Backend und Rendern der Ergebnisse
function loadCourses(categoryId = '', onlyFree = false, searchQuery = '') {
  getCoursesRequest(categoryId, onlyFree, searchQuery)
    .then(data => renderCoursesList(data));
}