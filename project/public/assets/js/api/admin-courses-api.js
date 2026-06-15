// --------------------------------------------------
// Admin Courses API Requests
// --------------------------------------------------


// --------------------------------------------------
// Alle Kurse abrufen
function getAdminCoursesRequest() {
  return fetch('/api/serviceHandler.php?module=adminCourses&action=get')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Alle Kurskategorien abrufen
function getAdminCourseCategoriesRequest() {
  return fetch('/api/serviceHandler.php?module=adminCourses&action=getCategories')
    .then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kurse erstellen
function createAdminCourseRequest(formData) {
  return fetch('/api/serviceHandler.php?module=adminCourses&action=create', {
    method: 'POST',
    body: formData
  }).then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kurse aktualisieren
function updateAdminCourseRequest(formData) {
  return fetch('/api/serviceHandler.php?module=adminCourses&action=update', {
    method: 'POST',
    body: formData
  }).then(res => res.json());
}
// --------------------------------------------------


// --------------------------------------------------
// Kurse löschen
function deleteAdminCourseRequest(courseId) {
  return fetch('/api/serviceHandler.php?module=adminCourses&action=delete', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: courseId
    })
  }).then(res => res.json());
}
// --------------------------------------------------