document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("course-details");
    const courseId = container.dataset.courseId;

    console.log('courseId:', courseId);

    if (!courseId) {
        showAuthAlert('Keine Kurs-ID gefunden.', 'danger');
    }

    loadCourseDetails(courseId);
});

function loadCourseDetails(courseId) {
  fetch(`/api/courses/get_course_details.php?id=${courseId}`)
    .then(res => res.json())
    .then(data => {
      console.log(data);

      if (data.success === false) {
        showAuthAlert(data.message, 'danger');
        return;
      }

      $('#course-details').html(`
        <div class="row g-4">

          <div class="col-lg-6">
            <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
              <img src="${data.course_image ?? ''}" 
                   class="img-fluid object-fit-cover"
                   alt="${data.course_image_alt ?? ''}">
            </div>
          </div>

          <div class="col-lg-6 d-flex flex-column">
            <h2 class="fw-bold mb-3">${data.title ?? ''}</h2>

            <span class="badge bg-light text-dark mb-3 align-self-start">
              ${data.category_name ?? ''}
            </span>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="fw-bold text-primary fs-4">${data.price ?? ''} €</span>
              <span class="text-warning fs-5">⭐ ${data.rating ?? ''}</span>
            </div>

            <p class="text-muted mb-4">
              ${(data.description ?? '').replace(/\n/g, '<br>')}
            </p>

            ${data.lecturer_name ? `
              <div class="small text-muted mb-1">
                <strong>Dozent:</strong> ${data.lecturer_name}
              </div>
            ` : ''}

            ${data.lecturer_contact ? `
              <div class="small text-muted mb-3">
                <strong>Kontakt:</strong> ${data.lecturer_contact}
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

            <button class="btn btn-primary rounded-pill mt-auto">
              Jetzt buchen
            </button>
          </div>

        </div>
      `);
    })
    .catch(() => {
        showAuthAlert('Fehler beim Laden des Kurses.', 'danger');
    });
}