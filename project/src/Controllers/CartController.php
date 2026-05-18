<?php

class CartController
{
    public function add(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);
        $courseId = isset($data['course_id']) ? (int)$data['course_id'] : 0;

        $cartService = new CartService();
        $result = $cartService->addToCart($courseId);

        echo json_encode($result);
        exit;
    }

    public function get(): void
    {
        header('Content-Type: application/json');

        $cartService = new CartService();
        $result = $cartService->getCart();

        echo json_encode($result);
        exit;
    }

    public function remove(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);
        $cartItemId = isset($data['cart_item_id']) ? (int)$data['cart_item_id'] : 0;

        $cartService = new CartService();
        $result = $cartService->removeFromCart($cartItemId);

        echo json_encode($result);
        exit;
    }
}