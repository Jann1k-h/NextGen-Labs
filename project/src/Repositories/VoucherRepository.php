<?php

class VoucherRepository
{
    public function findByCode(string $code): ?Voucher
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT *
            FROM vouchers
            WHERE code = :code
            LIMIT 1
        ");

        $stmt->execute([
            'code' => $code
        ]);

        $voucherData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$voucherData) {
            return null;
        }

        return new Voucher($voucherData);
    }

    public function increaseUsedCount(int $voucherId): void
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE vouchers
            SET used_count = used_count + 1
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $voucherId
        ]);
    }
}