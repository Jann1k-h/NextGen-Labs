<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Bestellhistorie</h1>
            <p class="text-body-secondary mb-0">Hier findest du deine bisherigen Bestellungen und Details.</p>
        </div>
    </div>

    <?php if (!empty($result['success']) && $result['success'] === false): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($result['message'] ?? 'Bestellhistorie konnte nicht geladen werden.') ?>
        </div>

    <?php elseif (empty($result['orders'])): ?>

        <div class="alert alert-info">
            Sie haben noch keine Bestellungen.
        </div>

    <?php else: ?>

        <!-- Bestellhistorie Card -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Meine Bestellungen</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>
                            <tr>
                                <th>Bestellung</th>
                                <th>Datum</th>
                                <th>Status</th>
                                <th>Gesamt</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($result['orders'] as $order): ?>

                                <tr>

                                    <td class="text-body-secondary">
                                        #<?= htmlspecialchars($order['id']) ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            'd.m.Y H:i',
                                            strtotime($order['created_at'])
                                        ) ?>
                                    </td>

                                    <td>

                                        <?php
                                        $statusClass = match ($order['status']) {
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'warning'
                                        };
                                        ?>

                                        <span class="badge text-bg-<?= $statusClass ?>">
                                            <?= htmlspecialchars($order['status']) ?>
                                        </span>

                                    </td>

                                    <td>
                                        <?= number_format(
                                            (float)$order['total_amount'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?> €
                                    </td>

                                    <td>

                                        <a href="/order-success.php?order_id=<?= htmlspecialchars($order['id']) ?>"
                                           class="btn btn-outline-primary btn-sm" target="_blank">
                                            Details
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    <?php endif; ?>

</div>