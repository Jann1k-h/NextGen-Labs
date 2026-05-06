document.addEventListener("DOMContentLoaded", () => {
  loadCoursesCategories();
});

function loadCoursesCategories() {
  getCoursesCategoriesRequest()
  .then(data => {
    renderCoursesCategories(data);
  });
}

$(document).on('change', '#category-select', function() {
  triggerCourseReload();
});

$(document).on('change', '#free-courses-checkbox', function() {
  triggerCourseReload();
});

function triggerCourseReload() {
  const categoryId = $('#category-select').val();
  const onlyFree = $('#free-courses-checkbox').is(':checked');

  console.log('Category:', categoryId);
  console.log('Only free:', onlyFree);

  // Funktion ist ist in courses.js
  loadCourses(categoryId, onlyFree);
}