function loadCourses(categoryId = '') {

  let url = '/api/courses/get_courses.php';

  if (categoryId) {
      url += '?category_id=' + categoryId;
  }

  fetch(url)
    .then(res => res.json())
    .then(data => {
      console.log(data);
      // Vorhandene Kurse entfernen
      $('#course-list').empty();

      data.forEach(course => {
        $('#course-list').append(`
          <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">

              <!-- Image -->
              <div class="ratio ratio-16x9">
                <img src="${course.course_image}" 
                    class="img-fluid object-fit-cover" 
                    alt="${course.course_image_alt}">
              </div>

              <!-- Body -->
              <div class="card-body d-flex flex-column">

                <!-- Title -->
                <h5 class="card-title fw-semibold mb-2">${course.title}</h5>

                <!-- Category -->
                <span class="badge bg-light text-dark mb-2 align-self-start">
                  ${course.category_name}
                </span>

                <!-- Price + Rating -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-bold text-primary fs-5">${course.price} €</span>
                  <small class="text-warning">⭐ ${course.rating}</small>
                </div>

                <!-- Lecturer -->
                ${course.lecturer_name ? `
                  <div class="small text-muted mb-1">
                    <strong>Dozent:</strong> ${course.lecturer_name}
                  </div>` : ''}

                ${course.lecturer_contact ? `
                  <div class="small text-muted mb-2">
                    <strong>Kontakt:</strong> ${course.lecturer_contact}
                  </div>` : ''}

                <!-- Stock -->
                <div class="mb-3">
                  ${course.stock == 0 
                    ? '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Ausverkauft</span>' 
                    : '<span class="badge bg-success-subtle text-success border border-success-subtle">' + course.stock + ' verfügbar</span>'}
                </div>

                <!-- Button -->
                <a href="/course/${course.id}" 
                  class="btn btn-primary w-100 mt-auto rounded-pill">
                  Zum Kurs
                </a>

              </div>
            </div>
          </div>
        `
      );
    });
  });

}

document.addEventListener("DOMContentLoaded", () => {
  loadCourses();
});