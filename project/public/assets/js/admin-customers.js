// --------------------------------------------------
// Admin Kundenverwaltung UI + Events
// --------------------------------------------------

// --------------------------------------------------
// Beim Laden der Seite Kunden laden
$(document).ready(() => {
  loadAdminCustomers();
});
// --------------------------------------------------


// --------------------------------------------------
// Kundendaten laden und UI aktualisieren
function loadAdminCustomers() {
  return getAdminCustomersRequest()
    .then(data => {
      $('#admin-customer-list').empty();

      if (data.success) {
        renderAdminCustomers(data.customers);
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Kunden anzeigen
function renderAdminCustomers(customers) {
  if (customers.length === 0) {
    $('#admin-customer-list').html(`
      <tr>
        <td colspan="9" class="text-muted text-center py-4">
          Keine Kunden vorhanden.
        </td>
      </tr>
    `);
    return;
  }

  customers.forEach(customer => {
    $('#admin-customer-list').append(`
      <tr>
        <td>${customer.id}</td>

        <td>
          ${escapeHtml(customer.title ?? '')}
          ${escapeHtml(customer.firstname ?? '')}
          ${escapeHtml(customer.lastname ?? '')}
        </td>

        <td>${escapeHtml(customer.username)}</td>
        <td>${escapeHtml(customer.email)}</td>

        <td>
          ${escapeHtml(customer.address ?? '')}<br>
          <span class="text-muted small">
            ${escapeHtml(customer.zipcode ?? '')} ${escapeHtml(customer.city ?? '')}
          </span>
        </td>

        <td>
          ${customer.is_admin == 1 
            ? '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">Admin</span>' 
            : '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Kunde</span>'}
        </td>

        <td>
          ${customer.is_active == 1 
            ? '<span class="badge bg-success-subtle text-success border border-success-subtle">Aktiv</span>' 
            : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Deaktiviert</span>'}
        </td>

        <td>${customer.created_at ?? '-'}</td>

        <td>
          <a class="btn btn-sm btn-outline-primary"
            href="/orders.php?user_id=${customer.id}"
            target="_blank">
            Bestellungen
          </a>

          <button class="btn btn-sm btn-outline-secondary edit-customer-btn"
                  data-id="${customer.id}"
                  data-title="${escapeHtml(customer.title ?? '')}"
                  data-firstname="${escapeHtml(customer.firstname ?? '')}"
                  data-lastname="${escapeHtml(customer.lastname ?? '')}"
                  data-username="${escapeHtml(customer.username ?? '')}"
                  data-email="${escapeHtml(customer.email ?? '')}"
                  data-address="${escapeHtml(customer.address ?? '')}"
                  data-zipcode="${escapeHtml(customer.zipcode ?? '')}"
                  data-city="${escapeHtml(customer.city ?? '')}"
                  data-payment-info="${escapeHtml(customer.payment_info ?? '')}"
                  data-is-admin="${customer.is_admin}"
                  data-is-active="${customer.is_active}">
            Bearbeiten
          </button>

          ${customer.is_active == 1 ? `
            <button class="btn btn-sm btn-outline-danger deactivate-customer-btn"
                    data-id="${customer.id}">
              Deaktivieren
            </button>
          ` : ''}
        </td>
      </tr>
    `);
  });
}
// --------------------------------------------------


// --------------------------------------------------
// Bearbeiten Button
$(document).on('click', '.edit-customer-btn', function () {
  $('#customer-id').val($(this).data('id'));
  $('#customer-title').val($(this).data('title'));
  $('#customer-firstname').val($(this).data('firstname'));
  $('#customer-lastname').val($(this).data('lastname'));
  $('#customer-username').val($(this).data('username'));
  $('#customer-email').val($(this).data('email'));
  $('#customer-address').val($(this).data('address'));
  $('#customer-zipcode').val($(this).data('zipcode'));
  $('#customer-city').val($(this).data('city'));
  $('#customer-payment-info').val($(this).attr('data-payment-info') ?? '');
  $('#customer-password').val('');
  $('#customer-is-admin').prop('checked', $(this).data('is-admin') == 1);
  $('#customer-is-active').prop('checked', $(this).data('is-active') == 1);

  openCustomerEditModal();
});
// --------------------------------------------------


// --------------------------------------------------
// Kunden bearbeiten Formular absenden
$(document).on('submit', '#customer-edit-form', function (e) {
  e.preventDefault();

  const customerData = {
    id: $('#customer-id').val(),
    title: $('#customer-title').val(),
    firstname: $('#customer-firstname').val().trim(),
    lastname: $('#customer-lastname').val().trim(),
    username: $('#customer-username').val().trim(),
    email: $('#customer-email').val().trim(),
    address: $('#customer-address').val().trim(),
    zipcode: $('#customer-zipcode').val().trim(),
    city: $('#customer-city').val().trim(),
    payment_info: $('#customer-payment-info').val().trim(),
    password: $('#customer-password').val().trim(),
    is_admin: $('#customer-is-admin').is(':checked') ? 1 : 0,
    is_active: $('#customer-is-active').is(':checked') ? 1 : 0
  };

  updateAdminCustomer(customerData);
});
// --------------------------------------------------


// --------------------------------------------------
// Kunde bearbeiten
function updateAdminCustomer(customerData) {
  return updateAdminCustomerRequest(customerData)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        closeCustomerEditModal();
        loadAdminCustomers();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Deaktivieren Button
$(document).on('click', '.deactivate-customer-btn', function () {
  const customerId = $(this).data('id');

  if (!confirm('Kunden wirklich deaktivieren?')) {
    return;
  }

  deactivateAdminCustomer(customerId);
});
// --------------------------------------------------


// --------------------------------------------------
// Kunde deaktivieren
function deactivateAdminCustomer(customerId) {
  return deactivateAdminCustomerRequest(customerId)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        loadAdminCustomers();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Bestellungen Button
$(document).on('click', '.show-orders-btn', function () {
  const customerId = $(this).data('id');
  const customerName = $(this).data('name');

  loadCustomerOrders(customerId, customerName);
});
// --------------------------------------------------


// --------------------------------------------------
// Bestellungen eines Kunden laden
function loadCustomerOrders(customerId, customerName) {
  return getAdminCustomerOrdersRequest(customerId)
    .then(data => {
      if (data.success) {
        $('#customerOrdersModalLabel').text('Bestellungen anzeigen');
        $('#customer-orders-subtitle').text(customerName);
        renderCustomerOrders(data.orders);
        openCustomerOrdersModal();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Bestellungen anzeigen
function renderCustomerOrders(orders) {
  if (orders.length === 0) {
    $('#customer-orders-list').html(`
      <p class="text-muted mb-0">
        Dieser Kunde hat noch keine Bestellungen.
      </p>
    `);
    return;
  }

  let html = `
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Bestell-ID</th>
            <th>Status</th>
            <th>Gesamtbetrag</th>
            <th>Rabatt</th>
            <th>Gutschein</th>
            <th>Rechnungsdaten</th>
            <th>Erstellt am</th>
          </tr>
        </thead>
        <tbody>
  `;

  orders.forEach(order => {
    html += `
      <tr>
        <td>${order.id}</td>
        <td>${escapeHtml(order.status ?? '-')}</td>
        <td>${Number(order.total_amount ?? 0).toFixed(2)} €</td>
        <td>${Number(order.discount_amount ?? 0).toFixed(2)} €</td>
        <td>${escapeHtml(order.voucher_code ?? '-')}</td>
        <td>
          ${escapeHtml(order.billing_title ?? '')}
          ${escapeHtml(order.billing_firstname ?? '')}
          ${escapeHtml(order.billing_lastname ?? '')}<br>
          <span class="text-muted small">
            ${escapeHtml(order.billing_email ?? '')}
          </span>
        </td>
        <td>${order.created_at ?? '-'}</td>
      </tr>
    `;
  });

  html += `
        </tbody>
      </table>
    </div>
  `;

  $('#customer-orders-list').html(html);
}
// --------------------------------------------------


// --------------------------------------------------
// Kunden Bearbeiten Modal öffnen
function openCustomerEditModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('customerEditModal')).show();
}
// --------------------------------------------------


// --------------------------------------------------
// Kunden Bearbeiten Modal schließen
function closeCustomerEditModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('customerEditModal')).hide();
}
// --------------------------------------------------


// --------------------------------------------------
// Bestellungen Modal öffnen
function openCustomerOrdersModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('customerOrdersModal')).show();
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