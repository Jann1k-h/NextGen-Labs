document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("course-details");

    if (!container) return;

    const courseId = container.dataset.courseId;

    console.log('courseId:', courseId);

    if (!courseId) {
        showAuthAlert('Keine Kurs-ID gefunden.', 'danger');
        return;
    }

    loadCourseDetails(courseId);
});

function loadCourseDetails(courseId) {
  getCourseDetailsRequest(courseId)
    .then(data => {
      console.log(data);

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