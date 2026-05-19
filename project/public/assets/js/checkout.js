// --------------------------------------------------
// Checkout UI + Daten laden
// --------------------------------------------------

$(document).ready(function () {
  loadCheckoutData();
});

function loadCheckoutData() {
  getCheckoutDataRequest()
    .then(data => {
      if (!data.success) {
        showAuthAlert(data.message, 'danger');
        return;
      }

      renderCheckoutDetails(data);
    })
}

function renderCheckoutDetails(data) {
  const user = data.user ?? {};
  const items = data.items ?? [];
  const total = Number(data.total ?? 0);

  $('#checkout-details').html(`

    <div class="container py-4">
      <div class="border rounded-4 shadow-sm p-3 p-md-4 bg-body">

        <div class="mb-4">
          <h2 class="fw-bold mb-1">Checkout</h2>
          <p class="text-body-secondary mb-0">
            Bitte überprüfe deine Rechnungsdaten und gib an, wer für die Kurse eingeschrieben wird.
          </p>
        </div>

        <div class="row g-4">

          <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 bg-body mb-4">
              <div class="card-body p-4">

                <h5 class="fw-bold mb-4">Rechnungsdaten</h5>

                <div class="row g-3">

                  <div class="col-md-4">
                    <label for="billing_title" class="form-label">Anrede</label>
                    <input type="text"
                           class="form-control rounded-pill"
                           id="billing_title"
                           name="billing_title"
                           value="${user.title ?? ''}">
                  </div>

                  <div class="col-md-4">
                    <label for="billing_firstname" class="form-label">Vorname</label>
                    <input type="text"
                           class="form-control rounded-pill"
                           id="billing_firstname"
                           name="billing_firstname"
                           value="${user.firstname ?? ''}">
                  </div>

                  <div class="col-md-4">
                    <label for="billing_lastname" class="form-label">Nachname</label>
                    <input type="text"
                           class="form-control rounded-pill"
                           id="billing_lastname"
                           name="billing_lastname"
                           value="${user.lastname ?? ''}">
                  </div>

                  <div class="col-12">
                    <label for="billing_address" class="form-label">Adresse</label>
                    <input type="text"
                           class="form-control rounded-pill"
                           id="billing_address"
                           name="billing_address"
                           value="${user.address ?? ''}">
                  </div>

                  <div class="col-md-4">
                    <label for="billing_zipcode" class="form-label">PLZ</label>
                    <input type="text"
                           class="form-control rounded-pill"
                           id="billing_zipcode"
                           name="billing_zipcode"
                           value="${user.zipcode ?? ''}">
                  </div>

                  <div class="col-md-8">
                    <label for="billing_city" class="form-label">Stadt</label>
                    <input type="text"
                           class="form-control rounded-pill"
                           id="billing_city"
                           name="billing_city"
                           value="${user.city ?? ''}">
                  </div>

                  <div class="col-12">
                    <label for="billing_email" class="form-label">E-Mail</label>
                    <input type="email"
                           class="form-control rounded-pill"
                           id="billing_email"
                           name="billing_email"
                           value="${user.email ?? ''}">
                  </div>

                  <div class="col-12">
                    <label for="payment_method" class="form-label">Zahlungsart</label>
                    <textarea class="form-control rounded-4"
                              id="payment_method"
                              name="payment_method"
                              rows="3">${user.payment_info ?? ''}</textarea>
                  </div>

                </div>

              </div>
            </div>

            <div class="card border shadow-sm rounded-4 bg-body">
              <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Teilnehmerdaten</h5>

                ${renderCheckoutItems(items, user)}
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card border shadow-sm rounded-4 bg-body h-100">
              <div class="card-body d-flex flex-column p-4">

                <h5 class="fw-bold mb-3">Bestellübersicht</h5>

                <div class="mb-4">
                  ${renderOrderSummary(items)}
                </div>

                <div class="border-top pt-3 mb-4">
                  <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Gesamt</span>
                    <span>${total.toFixed(2)} €</span>
                  </div>
                </div>

                <p class="text-body-secondary small mb-4">
                  Deine Rechnungsdaten werden für die Bestellung verwendet.
                </p>

                <div class="mt-auto d-grid gap-2">
                  <button class="btn btn-primary rounded-pill" id="place-order-btn" type="button">
                    Bestellung abschließen
                  </button>

                  <a href="/cart.php" class="btn btn-outline-secondary rounded-pill">
                    Zurück zum Warenkorb
                  </a>
                </div>

              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  `);
}

function renderCheckoutItems(items, user) {
  if (items.length === 0) {
    return `
      <p class="text-muted mb-0">
        Dein Warenkorb ist leer.
      </p>
    `;
  }

  const defaultParticipant = `${user.firstname ?? ''} ${user.lastname ?? ''}`.trim();

  return items.map(item => {
    const price = Number(item.price ?? 0);
    const quantity = Number(item.quantity ?? 1);
    const itemTotal = price * quantity;

    return `
      <div class="border rounded-4 p-3 mb-3 checkout-item">

        <div class="d-flex gap-3 align-items-start mb-3">
          <img src="${item.image_path ?? ''}"
               alt=""
               class="rounded"
               style="width: 90px; height: 65px; object-fit: cover;">

          <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">${item.title ?? ''}</h6>
            <div class="text-body-secondary small mb-1">
              Menge: ${quantity}
            </div>
            <div class="fw-semibold">
              ${itemTotal.toFixed(2)} €
            </div>
          </div>
        </div>

        <div>
          <label class="form-label" for="course_for_${item.course_id}">
            Teilnehmer / eingeschriebene Person
          </label>
          <input type="text"
                 class="form-control rounded-pill participant-input"
                 id="course_for_${item.course_id}"
                 name="course_for[${item.course_id}]"
                 data-course-id="${item.course_id}"
                 value="${defaultParticipant}"
                 placeholder="Name der teilnehmenden Person"
                 required>
        </div>

      </div>
    `;
  }).join('');
}

function renderOrderSummary(items) {
  if (items.length === 0) {
    return `
      <p class="text-muted mb-0">
        Keine Kurse im Warenkorb.
      </p>
    `;
  }

  return items.map(item => {
    const price = Number(item.price ?? 0);
    const quantity = Number(item.quantity ?? 1);
    const itemTotal = price * quantity;

    return `
      <div class="d-flex justify-content-between align-items-start border-bottom py-2">
        <div class="pe-2">
          <div class="fw-semibold small">${item.title ?? ''}</div>
          <div class="text-body-secondary small">Menge: ${quantity}</div>
        </div>
        <div class="fw-semibold small text-nowrap">
          ${itemTotal.toFixed(2)} €
        </div>
      </div>
    `;
  }).join('');
}