// --------------------------------------------------
// Kursdetails laden und rendern
// --------------------------------------------------

// --------------------------------------------------
// Beim Laden der Seite Kursdetails initalisieren
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("course-details");

    if (!container) {
        return;
    }

    const courseId = container.dataset.courseId;

    if (!courseId) {
        showAuthAlert('Keine Kurs-ID gefunden.', 'danger');
        return;
    }

    loadCourseDetails(courseId);
});
// --------------------------------------------------


// --------------------------------------------------
// Kursdetails laden
function loadCourseDetails(courseId) {
  getCourseDetailsRequest(courseId)
    .then(data => {

      if (data.success === false) {
        showAuthAlert(data.message, 'danger');
        return;
      }

      renderCourseDetails(data);
    })
    .catch(() => {
      showAuthAlert('Fehler beim Laden des Kurses.', 'danger');
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Kursdetails rendern
function renderCourseDetails(data) {
  $('#course-details').html(`

        <div class="container py-4">
            <div class="course-detail-wrapper border-0 rounded-4 shadow-sm p-3 p-md-4 bg-body">
                <div class="row g-4 align-items-stretch">

                    <div class="col-lg-6">
                        <div class="course-detail-image-card border-0 shadow-sm rounded-4 overflow-hidden bg-body h-100">
                            <div class="ratio ratio-16x9 bg-body-secondary">
                                <img src="${data.course_image ?? ''}" 
                                    class="img-fluid object-fit-cover"
                                    alt="${data.course_image_alt ?? ''}">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="course-detail-info-card border-0 shadow-sm rounded-4 bg-body h-100">
                        <div class="card-body d-flex flex-column h-100 p-4">

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                ${data.category_name ?? ''}
                            </span>

                            <span class="text-warning fw-semibold fs-5">
                                ⭐ ${data.rating ?? ''}
                            </span>
                            </div>

                            <h2 class="fw-bold mb-3 lh-sm">${data.title ?? ''}</h2>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 pb-3 border-bottom">
                                <span class="fw-bold text-primary fs-3">${data.price ?? ''} €</span>

                                ${parseInt(data.stock) === 0
                                    ? `<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">
                                        Ausverkauft
                                    </span>`
                                    : `<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                        ${data.stock} verfügbar
                                    </span>`
                                }
                            </div>

                            <p class="text-body-secondary mb-4">
                            ${(data.description ?? '').replace(/\n/g, '<br>')}
                            </p>

                            <div class="mb-4">
                            ${data.lecturer_name ? `
                            <div class="small text-body-secondary mb-2">
                                <strong class="text-body">Dozent:</strong> ${data.lecturer_name}
                            </div>
                            ` : ''}

                            ${data.lecturer_contact ? `
                            <div class="small text-body-secondary mb-2">
                                <strong class="text-body">Kontakt:</strong> ${data.lecturer_contact}
                            </div>
                            ` : ''}
                            </div>

                            <div class="mt-auto d-grid">
                              <button class="btn btn-primary rounded-pill fw-semibold py-2 add-to-cart-btn" data-id="${data.id}">
                                In den Warenkorb
                              </button>
                            </div>

                        </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
      `);
}
// --------------------------------------------------