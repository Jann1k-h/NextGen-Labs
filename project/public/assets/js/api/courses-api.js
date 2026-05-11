function getCourseDetailsRequest(courseId) {
  return fetch(`/api/serviceHandler.php?module=courses&action=getDetails&id=${courseId}`)
    .then(res => res.json());
}

function getCoursesCategoriesRequest() {
  return fetch('/api/serviceHandler.php?module=courses&action=getCategories')
    .then(res => res.json());
}

function getCoursesRequest(categoryId = '', onlyFree = false, searchQuery = '') {
  return fetch(`/api/serviceHandler.php?module=courses&action=search&category_id=${categoryId}&free=${onlyFree}&query=${encodeURIComponent(searchQuery)}`)
    .then(res => res.json());
}