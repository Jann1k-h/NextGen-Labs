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
                            <button class="btn btn-primary rounded-pill">
                                Jetzt buchen
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

            <button class="btn btn-outline-success w-100 rounded-pill add-to-cart-home-btn" data-id="${course.id}">
              In den Warenkorb
            </button>

          </div>
        </div>
      </div>
    `);
  });
}

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