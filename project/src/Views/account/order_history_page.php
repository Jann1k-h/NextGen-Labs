<div class="container py-4">

    <h2 class="mb-4">
        Bestellhistorie
    </h2>

    <?php if (empty($result['orders'])): ?>

        <div class="alert alert-info">
            Sie haben noch keine Bestellungen.
        </div>

    <?php else: ?>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Bestellung</th>
                        <th>Datum</th>
                        <th>Status</th>
                        <th>Gesamt</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($result['orders'] as $order): ?>

                        <tr>

                            <td>
                                #<?= $order['id'] ?>
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

                                <span class="badge bg-<?= $statusClass ?>">
                                    <?= htmlspecialchars($order['status']) ?>
                                </span>

                            </td>

                            <td>
                                <?= number_format(
                                    $order['total_amount'],
                                    2,
                                    ',',
                                    '.'
                                ) ?> €
                            </td>

                            <td>

                                <a href="/order-success.php?order_id=<?= $order['id'] ?>"
                                    class="btn btn-outline-primary btn-sm">
                                    Details
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>