<div class="container py-5">

    <?php if (!$result || !$result['success']): ?>

        <div class="alert alert-danger">
            Die Bestellung konnte nicht gefunden werden.
        </div>

        <a href="/" class="btn btn-outline-primary rounded-pill">
            Zurück zur Startseite
        </a>

    <?php else: ?>

        <?php
        $order = $result['order'];
        $items = $result['items'];

        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += (float)$item['price'] * (int)$item['quantity'];
        }

        $discountAmount = (float)$order['discount_amount'];
        $totalPrice = (float)$order['total_amount'];
        ?>

        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card border-success shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-success text-white text-center rounded-top-4">
                        <h3 class="mb-0">Bestellung erfolgreich!</h3>
                    </div>

                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </div>

                        <h4 class="text-success mb-3">Vielen Dank für deine Bestellung!</h4>

                        <p class="lead mb-0">
                            Deine Bestellnummer lautet:
                            <strong>#<?= htmlspecialchars($order['id']) ?></strong>
                        </p>
                    </div>
                </div>

                <div class="card border shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">Rechnung / Bestellübersicht</h4>
                                <p class="text-muted mb-0">
                                    Bestellt am:
                                    <?= htmlspecialchars(date('d.m.Y H:i', strtotime($order['created_at']))) ?>
                                </p>
                            </div>

                            <div class="text-lg-end">
                                <span class="badge bg-warning text-dark">
                                    Status: <?= htmlspecialchars($order['status']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <h5 class="fw-bold mb-3">Rechnungsdaten</h5>

                                    <p class="mb-1">
                                        <?= htmlspecialchars($order['billing_title']) ?>
                                        <?= htmlspecialchars($order['billing_firstname']) ?>
                                        <?= htmlspecialchars($order['billing_lastname']) ?>
                                    </p>

                                    <p class="mb-1">
                                        <?= htmlspecialchars($order['billing_address']) ?>
                                    </p>

                                    <p class="mb-1">
                                        <?= htmlspecialchars($order['billing_zipcode']) ?>
                                        <?= htmlspecialchars($order['billing_city']) ?>
                                    </p>

                                    <p class="mb-0">
                                        <?= htmlspecialchars($order['billing_email']) ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <h5 class="fw-bold mb-3">Zahlung</h5>

                                    <p class="mb-1">
                                        Zahlungsart:
                                        <strong><?= htmlspecialchars($order['billing_payment_info']) ?></strong>
                                    </p>

                                    <?php if (!empty($order['voucher_code'])): ?>
                                        <p class="mb-1">
                                            Gutschein:
                                            <strong><?= htmlspecialchars($order['voucher_code']) ?></strong>
                                        </p>

                                        <p class="mb-0 text-success">
                                            Rabatt:
                                            -<?= number_format($discountAmount, 2, ',', '.') ?> €
                                        </p>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">
                                            Kein Gutschein verwendet.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                        <h5 class="fw-bold mb-3">Kursdaten</h5>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Kurs</th>
                                        <th>Teilnehmer</th>
                                        <th class="text-center">Menge</th>
                                        <th class="text-end">Einzelpreis</th>
                                        <th class="text-end">Gesamt</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php
                                        $quantity = (int)$item['quantity'];
                                        $price = (float)$item['price'];
                                        $itemTotal = $quantity * $price;
                                        ?>

                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if (!empty($item['image_path'])): ?>
                                                        <img src="<?= htmlspecialchars($item['image_path']) ?>"
                                                             alt=""
                                                             class="rounded"
                                                             style="width: 70px; height: 50px; object-fit: cover;">
                                                    <?php endif; ?>

                                                    <div>
                                                        <div class="fw-semibold">
                                                            <?= htmlspecialchars($item['title']) ?>
                                                        </div>

                                                        <?php if (!empty($item['lecturer_name'])): ?>
                                                            <div class="text-muted small">
                                                                Vortragende/r:
                                                                <?= htmlspecialchars($item['lecturer_name']) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($item['course_for']) ?>
                                            </td>

                                            <td class="text-center">
                                                <?= $quantity ?>
                                            </td>

                                            <td class="text-end">
                                                <?= number_format($price, 2, ',', '.') ?> €
                                            </td>

                                            <td class="text-end fw-semibold">
                                                <?= number_format($itemTotal, 2, ',', '.') ?> €
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-4">
                            <div class="col-md-5">
                                <div class="border rounded-4 p-3">

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Zwischensumme</span>
                                        <span><?= number_format($subtotal, 2, ',', '.') ?> €</span>
                                    </div>

                                    <?php if ($discountAmount > 0): ?>
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Gutscheinrabatt</span>
                                            <span>-<?= number_format($discountAmount, 2, ',', '.') ?> €</span>
                                        </div>
                                    <?php endif; ?>

                                    <hr>

                                    <div class="d-flex justify-content-between fw-bold fs-5">
                                        <span>Endpreis</span>
                                        <span><?= number_format($totalPrice, 2, ',', '.') ?> €</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap mt-4">
                            <a href="/" class="btn btn-outline-success rounded-pill px-4">
                                Zurück zur Startseite
                            </a>

                            <button onclick="window.print()" class="btn btn-success rounded-pill px-4">
                                Rechnung drucken
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    <?php endif; ?>

</div>