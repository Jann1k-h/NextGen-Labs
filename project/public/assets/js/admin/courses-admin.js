// --------------------------------------------------
// Admin Kursverwaltung UI + Events
// --------------------------------------------------

$(document).ready(() => {
  loadAdminCourseCategories();
  loadAdminCourses();
});
// --------------------------------------------------


// --------------------------------------------------
// Kurse laden
function loadAdminCourses() {
  return getAdminCoursesRequest()
    .then(data => {
      $('#admin-course-list').empty();

      if (data.success) {
        renderAdminCourses(data.courses);
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Kategorien laden
function loadAdminCourseCategories() {
  return getAdminCourseCategoriesRequest()
    .then(data => {
      $('#course-category-id').empty();
      $('#course-category-id').append('<option value="">Kategorie auswählen</option>');

      if (data.success) {
        data.categories.forEach(category => {
          $('#course-category-id').append(`
            <option value="${category.id}">${category.name}</option>
          `);
        });
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Kurse rendern
function renderAdminCourses(courses) {
  if (courses.length === 0) {
    $('#admin-course-list').html(`
      <tr>
        <td colspan="8" class="text-muted text-center py-4">
          Keine Kurse vorhanden.
        </td>
      </tr>
    `);
    return;
  }

  courses.forEach(course => {
    $('#admin-course-list').append(`
      <tr>
        <td>${course.id}</td>

        <td>
          ${course.course_image ? `
            <img src="${course.course_image}" 
                 alt="${course.course_image_alt ?? ''}" 
                 style="width: 80px; height: 55px; object-fit: cover;" 
                 class="rounded">
          ` : '-'}
        </td>

        <td>${course.title}</td>
        <td>${course.category_name ?? '-'}</td>
        <td>${Number(course.price).toFixed(2)} €</td>
        <td>${course.stock}</td>
        <td>${course.is_active == 1 ? 'Ja' : 'Nein'}</td>

        <td>
          <button class="btn btn-sm btn-outline-primary edit-course-btn"
                  data-id="${course.id}"
                  data-category-id="${course.category_id}"
                  data-title="${escapeHtml(course.title)}"
                  data-description="${escapeHtml(course.description ?? '')}"
                  data-price="${course.price}"
                  data-rating="${course.rating ?? ''}"
                  data-stock="${course.stock}"
                  data-lecturer-name="${escapeHtml(course.lecturer_name ?? '')}"
                  data-lecturer-contact="${escapeHtml(course.lecturer_contact ?? '')}"
                  data-alt-text="${escapeHtml(course.course_image_alt ?? '')}"
                  data-is-active="${course.is_active}">
            Bearbeiten
          </button>

          <button class="btn btn-sm btn-outline-danger delete-course-btn"
                  data-id="${course.id}">
            Deaktiveren
          </button>
        </td>
      </tr>
    `);
  });
}
// --------------------------------------------------


// --------------------------------------------------
// Modal für neuen Kurs öffnen
$(document).on('click', '#open-create-course-modal-btn', function () {
  resetCourseForm();

  $('#courseModalLabel').text('Kurs erstellen');
  $('#course-submit-btn').text('Kurs erstellen');

  openCourseModal();
});
// --------------------------------------------------


// --------------------------------------------------
// Bearbeiten Button
$(document).on('click', '.edit-course-btn', function () {
  resetCourseForm();

  $('#course-id').val($(this).data('id'));
  $('#course-category-id').val($(this).data('category-id'));
  $('#course-title').val($(this).data('title'));
  $('#course-description').val($(this).data('description'));
  $('#course-price').val($(this).data('price'));
  $('#course-rating').val($(this).data('rating'));
  $('#course-stock').val($(this).data('stock'));
  $('#course-lecturer-name').val($(this).data('lecturer-name'));
  $('#course-lecturer-contact').val($(this).data('lecturer-contact'));
  $('#course-image-alt').val($(this).data('alt-text'));
  $('#course-is-active').prop('checked', $(this).data('is-active') == 1);

  $('#courseModalLabel').text('Kurs bearbeiten');
  $('#course-submit-btn').text('Kurs aktualisieren');

  openCourseModal();
});
// --------------------------------------------------


// --------------------------------------------------
// Formular absenden
$(document).on('submit', '#admin-course-form', function (e) {
  e.preventDefault();

  const courseId = $('#course-id').val();
  const formData = new FormData(this);

  formData.set('is_active', $('#course-is-active').is(':checked') ? 1 : 0);

  if (courseId) {
    updateAdminCourse(formData);
  } else {
    createAdminCourse(formData);
  }
});
// --------------------------------------------------


// --------------------------------------------------
// Kurs erstellen
function createAdminCourse(formData) {
  return createAdminCourseRequest(formData)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        closeCourseModal();
        resetCourseForm();
        loadAdminCourses();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Kurs bearbeiten
function updateAdminCourse(formData) {
  return updateAdminCourseRequest(formData)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        closeCourseModal();
        resetCourseForm();
        loadAdminCourses();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Deaktivieren Button
$(document).on('click', '.delete-course-btn', function () {
  const courseId = $(this).data('id');

  if (!confirm('Kurs wirklich deaktivieren?')) {
    return;
  }

  deleteAdminCourse(courseId);
});
// --------------------------------------------------


// --------------------------------------------------
// Kurs deaktivieren
function deleteAdminCourse(courseId) {
  return deleteAdminCourseRequest(courseId)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        loadAdminCourses();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Formular zurücksetzen
$(document).on('click', '#course-reset-btn', function () {
  resetCourseForm();
});

function resetCourseForm() {
  $('#course-id').val('');
  $('#admin-course-form')[0].reset();
  $('#course-is-active').prop('checked', true);
  $('#course-submit-btn').text('Kurs erstellen');
  $('#courseModalLabel').text('Kurs erstellen');
}
// --------------------------------------------------


// --------------------------------------------------
// Modal öffnen
function openCourseModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('courseModal')).show();
}
// --------------------------------------------------


// --------------------------------------------------
// Modal schließen
function closeCourseModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('courseModal')).hide();
}
// --------------------------------------------------


// --------------------------------------------------
// HTML escapen
function escapeHtml(value) {
  if (value === null || value === undefined) {
    return '';
  }

  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;');
}
// --------------------------------------------------