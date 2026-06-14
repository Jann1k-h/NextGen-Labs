// --------------------------------------------------
// Voucher UI + Events
// --------------------------------------------------

// --------------------------------------------------
// Beim Laden der Seite Gutscheine laden
$(document).ready(() => {
  loadVouchers();
});
// --------------------------------------------------


// --------------------------------------------------
// Voucher-Daten laden und UI aktualisieren
function loadVouchers() {
  return getVouchersRequest()
    .then(data => {
      $('#voucher-list').empty();

      if (data.success) {
        renderVouchers(data.vouchers);
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Gutscheine anzeigen
function renderVouchers(vouchers) {
  if (vouchers.length === 0) {
    $('#voucher-list').html(`
      <tr>
        <td colspan="10" class="text-muted text-center py-4">
          Keine Gutscheine vorhanden.
        </td>
      </tr>
    `);
    return;
  }

  vouchers.forEach(voucher => {
    $('#voucher-list').append(`
      <tr>
        <td>${voucher.id}</td>
        <td>${voucher.code}</td>
        <td>${voucher.name}</td>
        <td>${voucher.discount_type}</td>
        <td>${Number(voucher.discount_value).toFixed(2)}</td>
        <td>${voucher.valid_until ?? '-'}</td>
        <td>${voucher.usage_limit ?? '-'}</td>
        <td>${voucher.used_count}</td>
        <td>${voucher.is_active == 1 ? 'Ja' : 'Nein'}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary edit-voucher-btn"
                  data-id="${voucher.id}"
                  data-code="${escapeHtml(voucher.code)}"
                  data-name="${escapeHtml(voucher.name)}"
                  data-discount-type="${voucher.discount_type}"
                  data-discount-value="${voucher.discount_value}"
                  data-valid-until="${voucher.valid_until ?? ''}"
                  data-usage-limit="${voucher.usage_limit ?? ''}"
                  data-is-active="${voucher.is_active}">
            Bearbeiten
          </button>

          <button class="btn btn-sm btn-outline-danger delete-voucher-btn"
                  data-id="${voucher.id}">
            Löschen
          </button>
        </td>
      </tr>
    `);
  });
}
// --------------------------------------------------


// --------------------------------------------------
// Modal für neuen Gutschein öffnen
$(document).on('click', '#open-create-voucher-modal-btn', function () {
  resetVoucherForm();

  $('#voucherModalLabel').text('Gutschein erstellen');
  $('#voucher-submit-btn').text('Gutschein erstellen');

  openVoucherModal();
});
// --------------------------------------------------


// --------------------------------------------------
// Formular absenden: Gutschein erstellen oder bearbeiten
$(document).on('submit', '#voucher-form', function (e) {
  e.preventDefault();

  const voucherId = $('#voucher-id').val();

  const voucherData = {
    code: $('#voucher-code').val(),
    name: $('#voucher-name').val(),
    discount_type: $('#voucher-discount-type').val(),
    discount_value: $('#voucher-discount-value').val(),
    valid_until: $('#voucher-valid-until').val() || null,
    usage_limit: $('#voucher-usage-limit').val() || null,
    is_active: $('#voucher-is-active').is(':checked') ? 1 : 0
  };

  if (voucherId) {
    updateVoucher(voucherId, voucherData);
  } else {
    createVoucher(voucherData);
  }
});
// --------------------------------------------------


// --------------------------------------------------
// Bearbeiten Button
$(document).on('click', '.edit-voucher-btn', function () {
  resetVoucherForm();

  $('#voucher-id').val($(this).data('id'));
  $('#voucher-code').val($(this).data('code'));
  $('#voucher-name').val($(this).data('name'));
  $('#voucher-discount-type').val($(this).data('discount-type'));
  $('#voucher-discount-value').val($(this).data('discount-value'));
  $('#voucher-valid-until').val(formatDateTimeLocal($(this).data('valid-until')));
  $('#voucher-usage-limit').val($(this).data('usage-limit'));
  $('#voucher-is-active').prop('checked', $(this).data('is-active') == 1);

  $('#voucherModalLabel').text('Gutschein bearbeiten');
  $('#voucher-submit-btn').text('Gutschein aktualisieren');

  openVoucherModal();
});
// --------------------------------------------------


// --------------------------------------------------
// Löschen Button
$(document).on('click', '.delete-voucher-btn', function () {
  const voucherId = $(this).data('id');

  if (!confirm('Gutschein wirklich löschen?')) {
    return;
  }

  deleteVoucher(voucherId);
});
// --------------------------------------------------


// --------------------------------------------------
// Formular zurücksetzen
$(document).on('click', '#voucher-reset-btn', function () {
  resetVoucherForm();
});
// --------------------------------------------------


// --------------------------------------------------
// Gutschein erstellen
function createVoucher(voucherData) {
  return createVoucherRequest(voucherData)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        closeVoucherModal();
        resetVoucherForm();
        loadVouchers();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Gutschein bearbeiten
function updateVoucher(voucherId, voucherData) {
  return updateVoucherRequest(voucherId, voucherData)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        closeVoucherModal();
        resetVoucherForm();
        loadVouchers();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Gutschein löschen
function deleteVoucher(voucherId) {
  return deleteVoucherRequest(voucherId)
    .then(data => {
      if (data.success) {
        showAuthAlert(data.message, 'success');
        loadVouchers();
      } else {
        showAuthAlert(data.message, 'danger');
      }

      return data;
    });
}
// --------------------------------------------------


// --------------------------------------------------
// Formular zurücksetzen
function resetVoucherForm() {
  $('#voucher-id').val('');
  $('#voucher-form')[0].reset();
  $('#voucher-is-active').prop('checked', true);
  $('#voucher-submit-btn').text('Gutschein erstellen');
  $('#voucherModalLabel').text('Gutschein erstellen');
}
// --------------------------------------------------


// --------------------------------------------------
// Modal öffnen
function openVoucherModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('voucherModal')).show();
}
// --------------------------------------------------


// --------------------------------------------------
// Modal schließen
function closeVoucherModal() {
  bootstrap.Modal.getOrCreateInstance(document.getElementById('voucherModal')).hide();
}
// --------------------------------------------------


// --------------------------------------------------
// DATETIME aus DB für datetime-local Input formatieren
function formatDateTimeLocal(value) {
  if (!value) {
    return '';
  }

  return value.replace(' ', 'T').slice(0, 16);
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