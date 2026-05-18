// --------------------------------------------------
// Kursdetails laden und rendern
// --------------------------------------------------

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

function renderCourseDetails(data) {
  $('#course-details').html(`

        <div class="container py-4">
            <div class="border rounded-4 shadow-sm p-3 p-md-4 bg-body">
                <div class="row g-4 align-items-center">

                    <div class="col-lg-6">
                        <div class="card border shadow-sm rounded-4 overflow-hidden bg-body h-100">
                            <div class="ratio ratio-16x9">
                                <img src="${data.course_image ?? ''}" 
                                    class="img-fluid object-fit-cover"
                                    alt="${data.course_image_alt ?? ''}">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border shadow-sm rounded-4 bg-body h-100">
                        <div class="card-body d-flex flex-column h-100 p-4">

                            <div class="mb-3">
                            <span class="badge bg-body-secondary text-body mb-3">
                                ${data.category_name ?? ''}
                            </span>

                            <h2 class="fw-bold mb-2">${data.title ?? ''}</h2>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <span class="fw-bold text-primary fs-4">${data.price ?? ''} €</span>
                                <span class="text-warning fs-5">⭐ ${data.rating ?? ''}</span>
                            </div>
                            </div>

                            <p class="text-body-secondary mb-4">
                            ${(data.description ?? '').replace(/\n/g, '<br>')}
                            </p>

                            ${data.lecturer_name ? `
                            <div class="small text-body-secondary mb-2">
                                <strong class="text-body">Dozent:</strong> ${data.lecturer_name}
                            </div>
                            ` : ''}

                            ${data.lecturer_contact ? `
                            <div class="small text-body-secondary mb-3">
                                <strong class="text-body">Kontakt:</strong> ${data.lecturer_contact}
                            </div>
                            ` : ''}

                            <div class="mb-4">
                            ${parseInt(data.stock) === 0
                                ? `<span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    Ausverkauft
                                </span>`
                                : `<span class="badge bg-success-subtle text-success border border-success-subtle">
                                    ${data.stock} verfügbar
                                </span>`
                            }
                            </div>

                            <div class="mt-auto d-grid">
                              <button class="btn btn-primary rounded-pill add-to-cart-btn" data-id="${data.id}">
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