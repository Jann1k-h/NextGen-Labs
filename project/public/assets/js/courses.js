// --------------------------------------------------
// Kursliste, Filter, Kategorien und Rendering
// --------------------------------------------------


// --------------------------------------------------
// Initalisierung beim Laden der Seite
document.addEventListener("DOMContentLoaded", () => {
    if ($('#course-list').length) {
        loadCourses();
    }

    if ($('#category-select').length) {
        loadCoursesCategories();
    }
});
// --------------------------------------------------


// --------------------------------------------------
// Laden der Kurse vom Backend und Rendern der Ergebnisse
function loadCourses(categoryId = '', onlyFree = false, searchQuery = '') {
  getCoursesRequest(categoryId, onlyFree, searchQuery)
    .then(data => renderCoursesList(data));
}
// --------------------------------------------------


// --------------------------------------------------
// AJAX: Kategorien vom Backend holen und ins Dropdown rendern
function loadCoursesCategories() {
    getCoursesCategoriesRequest()
        .then(data => renderCoursesCategories(data));
}
// --------------------------------------------------


// --------------------------------------------------
// Filter-Events
// --------------------------------------------------


// --------------------------------------------------
// Eventlistener für Kategorie- und Free-Filter
$(document).on("change", "#category-select, #free-courses-checkbox", loadCoursesFromFilters);
// --------------------------------------------------


// --------------------------------------------------
// Eventlistener für Live-Suche
$(document).on("keyup", "#course-search", loadCoursesFromFilters);


// --------------------------------------------------
// Kurse nach Filtern neu laden
function loadCoursesFromFilters() {
    loadCourses(
        $("#category-select").val(),
        $("#free-courses-checkbox").is(":checked"),
        $("#course-search").val()
    );
}
// --------------------------------------------------


// --------------------------------------------------
// Kurse rendern
function renderCoursesList(data) {
  // Vorhandene Kurse entfernen
  $('#course-list').empty();

  data.forEach(course => {
    $('#course-list').append(`
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border rounded-4 overflow-hidden bg-body">

          <div class="ratio ratio-16x9">
            <img src="${course.course_image}" 
                class="img-fluid object-fit-cover" 
                alt="${course.course_image_alt}">
          </div>

          <div class="card-body d-flex flex-column">

            <h5 class="card-title fw-semibold mb-2">${course.title}</h5>

            <span class="badge bg-body-secondary text-body mb-2 align-self-start">
              ${course.category_name}
            </span>

            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold text-primary fs-5">${course.price} €</span>
              <small class="text-warning">⭐ ${course.rating}</small>
            </div>

            ${course.lecturer_name ? `
              <div class="small text-body-secondary mb-1">
                <strong>Dozent:</strong> ${course.lecturer_name}
              </div>` : ''}

            ${course.lecturer_contact ? `
              <div class="small text-body-secondary mb-2">
                <strong>Kontakt:</strong> ${course.lecturer_contact}
              </div>` : ''}

            <div class="mb-3">
              ${course.stock == 0 
                ? '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Ausverkauft</span>' 
                : '<span class="badge bg-success-subtle text-success border border-success-subtle">' + course.stock + ' verfügbar</span>'}
            </div>

            <a href="course_details.php?id=${course.id}" target="_blank" 
              class="btn btn-primary w-100 mt-auto rounded-pill">
              Zum Kurs
            </a>

            <button class="btn btn-outline-success w-100 rounded-pill add-to-cart-btn" data-id="${course.id}">
              In den Warenkorb
            </button>

          </div>
        </div>
      </div>
    `);
  });
}
// --------------------------------------------------


// --------------------------------------------------
// Kategorien rendern
function renderCoursesCategories(data) {
  console.log(data);

  data.forEach(courses_categories => {
    $('#category-select').append(`
      <option value="${courses_categories.id}">${courses_categories.name}</option>
    `);
  });

  // Vorhandene Kurse entfernen
  //$('#course-list').empty();
}
// --------------------------------------------------