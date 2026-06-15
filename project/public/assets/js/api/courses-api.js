// --------------------------------------------------
// Courses API Requests
// --------------------------------------------------


// --------------------------------------------------
// Kursdetails abrufen
function getCourseDetailsRequest(courseId) {
  return fetch(`/api/serviceHandler.php?module=courses&action=getDetails&id=${courseId}`)
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kurskategorien abrufen
function getCoursesCategoriesRequest() {
  return fetch('/api/serviceHandler.php?module=courses&action=getCategories')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kurssuche und filtern
function getCoursesRequest(categoryId = '', onlyFree = false, searchQuery = '') {
  return fetch(`/api/serviceHandler.php?module=courses&action=search&category_id=${categoryId}&free=${onlyFree}&query=${encodeURIComponent(searchQuery)}`)
    .then(res => res.text())
    .then(text => {
      console.log('Courses API Antwort:', text);
      return JSON.parse(text);
    });
}
// --------------------------------------------------