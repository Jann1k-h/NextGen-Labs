document.addEventListener("DOMContentLoaded", () => {
  loadCourses();
});

function loadCourses(categoryId = '', onlyFree = false) {
  getCoursesRequest(categoryId, onlyFree)
    .then(data => {
      console.log(data);

      renderCoursesList(data);
    });
}