<?php

class CartController
{
    public function add(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);
        $courseId = $data['course_id'];

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
        $cartItemId = $data['cart_item_id'];

        $cartService = new CartService();
        $result = $cartService->removeFromCart($cartItemId);

        echo json_encode($result);
        exit;
    }
}