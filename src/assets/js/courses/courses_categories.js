document.addEventListener("DOMContentLoaded", () => {
  loadCoursesCategories();
});

function loadCoursesCategories() {
  fetch('/api/courses/get_courses_categories.php')
  .then(res => res.json())
  .then(data => {
    console.log(data);

    data.forEach(courses_categories => {
      $('#category-select').append(`
        <option value="${courses_categories.id}">${courses_categories.name}</option>
      `);
    });

    // Vorhandene Kurse entfernen
    //$('#course-list').empty();

  });
}

$(document).on('change', '#category-select', function() {

  const categoryId = $(this).val();

  // Funktion ist ist in coureses.js
  loadCourses(categoryId);

  console.log('Selected category ID: ' + categoryId);

});