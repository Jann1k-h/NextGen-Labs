<?php

// zentrale Klasse, um User-Objekte zu repräsentieren

class User
{
    public int $id;
    public string $title;
    public string $firstname;
    public string $lastname;
    public string $username;
    public string $address;
    public string $zipcode;
    public string $city;
    public string $email;
    public string $password;
    public string $paymentInfo;
    public bool $isAdmin;
    public bool $isActive;
    public ?string $rememberToken;
    public ?string $rememberTokenExpires;

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->title = $data['title'] ?? '';
        $this->firstname = $data['firstname'] ?? '';
        $this->lastname = $data['lastname'] ?? '';
        $this->username = $data['username'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->zipcode = $data['zipcode'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->paymentInfo = $data['payment_info'] ?? '';
        $this->isAdmin = (bool)($data['is_admin'] ?? false);
        $this->isActive = (bool)($data['is_active'] ?? false);
        $this->rememberToken = $data['remember_token'] ?? null;
        $this->rememberTokenExpires = $data['remember_token_expires'] ?? null;
    }
}