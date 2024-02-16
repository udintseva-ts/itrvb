<?php

class Product
{
    public $id;
    public $title;
    public $description;
    public $price;
    public $img;
}

class ProductInCart extends Product
{
    public $amountInCart;
    public function __construct(ProductInStock $product, int $amount) {
        $this->id = $product->id;
        $this->title = $product->title;
        $this->price = $product->price;
        $this->description = $product->description;
        $this->img = $product->img;
        $this->amountInCart = $amount;
    }
}

class ProductInStock extends Product
{
    public $balanceInStock;
    public function can_add_in_cart(int $amount) : bool {
        if ($amount <= $this->balanceInStock) {
            $this->balanceInStock -= $amount;
            return true;
        } else {
            return false;
        }
    }
}

class User
{
    public $id;
    public $firstName;
    public $lastName;
    public $birthDate;

    public function __construct(int $id, string $firstName, string $lastName, DateTime $birthDate)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
    }
}
class Cart
{
    public $user;
    private $products = [];

    public function get_total_price() : int
    {
        $result = 0;
        foreach ($this->products as $item) {
            $result += $item->price * $item->amountInCart;
        }
        return $result;
    }

    public function add_product_in_cart(ProductInCart $product) : void
    {
        array_push($this->$product);
    }

    public function get_products_in_cart()
    {
        return $this->products;
    }

    public function __construct($user)
    {
        $this->user = $user;
    }
}

class Shop
{
    static function add_in_cart(ProductInStock $product, $amount, Cart $cart)
    {
        if ($product->can_add_in_cart($amount)) {
            $productInCart = new ProductInCart($product, $amount);
            $cart->add_product_in_cart($productInCart);
        } else {
            echo 'Недостаточно товаров на складе';
        }
    }
}
class Review
{
    public $user;
    public $product;
    public $text;
    private $rating;

    public function __construct(User $user, Product $product, int $rating)
    {
        $this->user = $user;
        $this->product = $product;
        $this->set_rating($rating);
    }

    public function set_rating(int $value)
    {
        if ($value <= 5 and $value >= 1) {
            $this->rating = $value;
        } else {
            echo 'Рейтинг должен быть от 1 до 5';
        }
    }

    public function get_rating() : int
    {
        return $this->rating;
    }
}
