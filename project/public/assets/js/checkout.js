// --------------------------------------------------
// Checkout UI + Daten laden
// --------------------------------------------------

$(document).ready(function () {
  loadCheckoutData();
});

function loadCheckoutData() {
  getCheckoutDataRequest()
    .then(data => {
      if (data.success == false) {
        showAuthAlert(data.message, 'danger');
        return;
      }

      renderCheckoutDetails(data);
    })
}

// --------------------------------------------------
// Gutschein prüfen
$(document).on('click', '#check-voucher-btn', function () {
  const voucherCode = $('#voucher_code').val().trim();

  if (voucherCode === '') {
    showVoucherMessage('Bitte gib einen Gutscheincode ein.', false);
    resetVoucherDiscount();
    return;
  }

  checkVoucherRequest(voucherCode)
    .then(data => {
      showVoucherMessage(data.message, data.success);

      if (data.success) {
        updateVoucherDiscount(data);
      } else {
        resetVoucherDiscount();
      }
    });
});

// --------------------------------------------------
// Bestellung abschließen
$(document).on('click', '#place-order-btn', function () {
  const participants = {};

  $('.participant-input').each(function () {
    const courseId = $(this).data('course-id');
    const participantName = $(this).val().trim();

    participants[courseId] = participantName;
  });

  const orderData = {
    billing_title: $('#billing_title').val(),
    billing_firstname: $('#billing_firstname').val().trim(),
    billing_lastname: $('#billing_lastname').val().trim(),
    billing_address: $('#billing_address').val().trim(),
    billing_zipcode: $('#billing_zipcode').val().trim(),
    billing_city: $('#billing_city').val().trim(),
    billing_email: $('#billing_email').val().trim(),
    payment_method: $('#payment_method').val(),
    voucher_code: $('#voucher_code').val().trim(),
    participants: participants
  };

  placeOrderRequest(orderData)
    .then(data => {
      if (!data.success) {
        showAuthAlert(data.message, 'danger');
        return;
      }

      window.location.href = '/order-success.php?order_id=' + data.order_id;
    });
});
// --------------------------------------------------


// Hilfsfunktionen zum Rendern und Anzeigen von Nachrichten
function showVoucherMessage(message, isSuccess) {
  $('#voucher-message')
    .text(message)
    .removeClass('text-success text-danger')
    .addClass(isSuccess ? 'text-success' : 'text-danger');
}

function resetVoucherDiscount() {
  $('#checkout-discount-row').addClass('d-none');
  $('#checkout-discount-amount').text('-0.00 €');

  const subtotal = Number($('#checkout-subtotal').text().replace('€', '').trim());
  $('#checkout-final-total').text(subtotal.toFixed(2) + ' €');
}

function updateVoucherDiscount(data) {
  $('#checkout-subtotal').text(Number(data.subtotal).toFixed(2) + ' €');
  $('#checkout-discount-row').removeClass('d-none');
  $('#checkout-discount-amount').text('-' + Number(data.discount_amount).toFixed(2) + ' €');
  $('#checkout-final-total').text(Number(data.final_total).toFixed(2) + ' €');
}
// --------------------------------------------------

// Wichtig das vorausfüllen bereits gespeicherter daten passiert am Ende der Funktion
function renderCheckoutDetails(data) {
  const user = data.user;
  const items = data.items;
  const total = Number(data.total);

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

          <!-- Links: Rechnungsdaten -->
          <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 bg-body h-100">
              <div class="card-body p-4">

                <h5 class="fw-bold mb-4">Rechnungsdaten</h5>

                <div class="row g-3">

                  <!-- Anrede -->
                  <div class="col-md-4">
                    <label for="billing_title" class="form-label">Anrede</label>
                    <select class="form-select rounded-pill" id="billing_title" name="billing_title">
                      <option value="">Anrede auswählen</option>
                      <option value="Herr">Herr</option>
                      <option value="Frau">Frau</option>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label for="billing_firstname" class="form-label">Vorname</label>
                    <input type="text" class="form-control rounded-pill" id="billing_firstname" name="billing_firstname">
                  </div>

                  <div class="col-md-4">
                    <label for="billing_lastname" class="form-label">Nachname</label>
                    <input type="text" class="form-control rounded-pill" id="billing_lastname" name="billing_lastname">
                  </div>

                  <div class="col-12">
                    <label for="billing_address" class="form-label">Adresse</label>
                    <input type="text" class="form-control rounded-pill" id="billing_address" name="billing_address">
                  </div>

                  <div class="col-md-4">
                    <label for="billing_zipcode" class="form-label">PLZ</label>
                    <input type="text" class="form-control rounded-pill" id="billing_zipcode" name="billing_zipcode">
                  </div>

                  <div class="col-md-8">
                    <label for="billing_city" class="form-label">Stadt</label>
                    <input type="text" class="form-control rounded-pill" id="billing_city" name="billing_city">
                  </div>

                  <div class="col-12">
                    <label for="billing_email" class="form-label">E-Mail</label>
                    <input type="email" class="form-control rounded-pill" id="billing_email" name="billing_email">
                  </div>

                  <!-- Zahlungsart -->
                  <div class="col-12">
                    <label for="payment_method" class="form-label">Zahlungsart</label>

                    <select class="form-select rounded-pill" id="payment_method" name="payment_method">
                      <option value="">Zahlungsmethode wählen</option>
                      <option value="paypal">PayPal</option>
                      <option value="invoice">Rechnung</option>
                      <option value="credit_card">Kreditkarte</option>
                    </select>

                    <div class="error text-danger small mt-1" id="payment-method-error"></div>
                  </div>

                </div>

              </div>
            </div>
          </div>

          <!-- Rechts: Teilnehmerdaten -->
          <div class="col-lg-4">
            <div class="card border shadow-sm rounded-4 bg-body h-100">
              <div class="card-body p-4">

                <h5 class="fw-bold mb-4">Teilnehmerdaten</h5>

                ${renderCheckoutItems(items, user)}

              </div>
            </div>
          </div>

        </div>

        <!-- Abschlussbox unten -->
        <div class="card border shadow-sm rounded-4 bg-body mt-4">
          <div class="card-body p-4">

            <!-- -------------------------------------------------- -->
            <!-- Gutschein -->
            <div class="mb-3">
              <label for="voucher_code" class="form-label">Gutscheincode</label>

              <div class="input-group">
                <input type="text"
                      class="form-control rounded-start-pill"
                      id="voucher_code"
                      placeholder="z. B. WELCOME10">

                <button class="btn btn-outline-primary rounded-end-pill"
                        type="button"
                        id="check-voucher-btn">
                  Gutschein prüfen
                </button>
              </div>

              <div id="voucher-message" class="small mt-2"></div>
            </div>
            <!-- -------------------------------------------------- -->

            <!-- -------------------------------------------------- -->
            <!-- Bestellübersicht -->
            <div class="border-bottom pb-3 mb-3">

              <div class="d-flex justify-content-between align-items-center mb-2">
                <span>Zwischensumme</span>
                <span id="checkout-subtotal">${total.toFixed(2)} €</span>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-2 text-success d-none" id="checkout-discount-row">
                <span>Gutscheinrabatt</span>
                <span id="checkout-discount-amount">-0.00 €</span>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold fs-5">Gesamt</span>
                <span class="fw-bold fs-5" id="checkout-final-total">${total.toFixed(2)} €</span>
              </div>

            </div>
            <!-- -------------------------------------------------- -->


            <p class="text-body-secondary small mb-4">
              Deine Rechnungsdaten werden für die Bestellung verwendet.
            </p>

            <div class="d-flex">
              <button class="btn btn-primary rounded-pill px-4 w-100 w-md-auto" id="place-order-btn" type="button">
                Bestellung abschließen
              </button>
            </div>

          </div>
        </div>

      </div>
    </div>
  `);

  $('#billing_title').val(user.title ?? '');
  $('#billing_firstname').val(user.firstname ?? '');
  $('#billing_lastname').val(user.lastname ?? '');
  $('#billing_address').val(user.address ?? '');
  $('#billing_zipcode').val(user.zipcode ?? '');
  $('#billing_city').val(user.city ?? '');
  $('#billing_email').val(user.email ?? '');
  $('#payment_method').val(user.payment_info ?? '');
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

  let html = '';

  for (const item of items) {
    const price = Number(item.price ?? 0);
    const quantity = Number(item.quantity ?? 1);
    const itemTotal = price * quantity;

    html += `
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
  }

  return html;
}

function renderOrderSummary(items) {
  if (items.length === 0) {
    return `
      <p class="text-muted mb-0">
        Keine Kurse im Warenkorb.
      </p>
    `;
  }

  let html = '';

  for (const item of items) {
    const price = Number(item.price ?? 0);
    const quantity = Number(item.quantity ?? 1);
    const itemTotal = price * quantity;

    html += `
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
  }

  return html;
}