<div class="container py-4">

  <div class="bg-body-tertiary border rounded-4 shadow-sm p-3 p-md-4 mb-4">

    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-5">
      
      <div class="me-lg-auto">
        <a class="navbar-brand fw-bold fs-3 m-0 text-decoration-none text-body" href="/">
          Kurse
        </a>
        <div class="text-body-secondary small text-nowrap">Entdecke passende Kurse für dich</div>
      </div>

      <div class="d-flex flex-column flex-md-row gap-2 w-100 w-lg-auto">

        <div class="flex-grow-1">
          <?php include __DIR__ . '/courses_search.php'; ?>
        </div>

        <div>
          <?php include __DIR__ . '/courses_filters.php'; ?>
        </div>
        
      </div>

    </div>
  </div>

  <div class="row g-4" id="course-list"></div>

</div>