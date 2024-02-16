<?php

abstract class Product
{
    public static $income;
    protected $price;

    abstract function buy($weghtOrNumber);

    public function __construct($price)
    {
        $this->price = $price;
    }
}

class UnitProduct extends Product
{
    public function buy($unitsNumber)
    {
        self::$income += $this->price * $unitsNumber;
    }
}


class WeightProduct extends Product
{
    public function buy($weight)
    {
        self::$income += $this->price * $weight;
    }
}

class DigitalProduct extends Product
{
    public $product;

    public function buy($weightOrNumber)
    {
        self::$income += $this->product->buy($weightOrNumber / 2);
    }

    public function __construct($product)
    {
        $this->product = $product;
    }
}