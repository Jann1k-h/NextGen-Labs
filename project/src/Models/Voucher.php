<?php

class Voucher
{
    public int $id;
    public string $code;
    public string $name;
    public string $discount_type;
    public float $discount_value;
    public ?string $valid_until;
    public ?int $usage_limit;
    public int $used_count;
    public bool $is_active;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->code = $data['code'];
        $this->name = $data['name'];
        $this->discount_type = $data['discount_type'];
        $this->discount_value = (float)$data['discount_value'];
        $this->valid_until = $data['valid_until'] ?? null;
        $this->usage_limit = isset($data['usage_limit']) ? (int)$data['usage_limit'] : null;
        $this->used_count = (int)$data['used_count'];
        $this->is_active = (bool)$data['is_active'];
    }
}