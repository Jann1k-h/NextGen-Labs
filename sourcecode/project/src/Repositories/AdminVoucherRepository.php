<?php

class AdminVoucherRepository
{
    // --------------------------------------------------
    // Gutschein anhand Code finden
    public function findByCode(string $code): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT *
            FROM vouchers
            WHERE code = :code
            LIMIT 1
        ");

        $stmt->execute([
            'code' => strtoupper(trim($code))
        ]);

        $voucherData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $voucherData ?: null;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein anhand ID finden
    public function findById(int $id): ?array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT *
            FROM vouchers
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $voucherData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $voucherData ?: null;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Alle Gutscheine laden
    public function findAll(): array
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT *
            FROM vouchers
            ORDER BY id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Neuen Gutschein erstellen
    public function create(array $data): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            INSERT INTO vouchers (
                code,
                name,
                discount_type,
                discount_value,
                valid_until,
                usage_limit,
                used_count,
                is_active
            ) VALUES (
                :code,
                :name,
                :discount_type,
                :discount_value,
                :valid_until,
                :usage_limit,
                0,
                :is_active
            )
        ");

        return $stmt->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'valid_until' => $data['valid_until'],
            'usage_limit' => $data['usage_limit'],
            'is_active' => $data['is_active']
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein bearbeiten
    public function update(int $id, array $data): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            UPDATE vouchers
            SET
                code = :code,
                name = :name,
                discount_type = :discount_type,
                discount_value = :discount_value,
                valid_until = :valid_until,
                usage_limit = :usage_limit,
                is_active = :is_active
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'code' => $data['code'],
            'name' => $data['name'],
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'valid_until' => $data['valid_until'],
            'usage_limit' => $data['usage_limit'],
            'is_active' => $data['is_active']
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Gutschein löschen
    public function delete(int $id): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            DELETE FROM vouchers
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Prüfen, ob Code bereits existiert
    public function existsByCode(string $code): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM vouchers
            WHERE code = :code
        ");

        $stmt->execute([
            'code' => strtoupper(trim($code))
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Prüfen, ob Code bei anderem Gutschein existiert
    public function existsByCodeExceptId(string $code, int $id): bool
    {
        $pdo = getDB();

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM vouchers
            WHERE code = :code
            AND id != :id
        ");

        $stmt->execute([
            'code' => strtoupper(trim($code)),
            'id' => $id
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }
    // --------------------------------------------------


    // --------------------------------------------------
    // Nutzungsanzahl erhöhen
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
    // --------------------------------------------------
}