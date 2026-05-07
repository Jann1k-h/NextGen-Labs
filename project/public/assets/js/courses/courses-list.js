document.addEventListener("DOMContentLoaded", () => {
    loadCourses();
});

// Funktion zum Laden der Kurse vom Backend und Rendern der Ergebnisse
function loadCourses(categoryId = '', onlyFree = false, searchQuery = '') {
    fetch(`/api/serviceHandler.php?module=courses&action=search&category_id=${categoryId}&free=${onlyFree}&query=${encodeURIComponent(searchQuery)}`)
        .then(res => res.json())
        .then(data => renderCoursesList(data));
}