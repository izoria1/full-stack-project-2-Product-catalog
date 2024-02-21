<?php

abstract class Product
{
    // Define product properties
    protected $sku;
    protected $name;
    protected $price;

    // Initialize a new Product instance with SKU, name, and price
    public function __construct($sku, $name, $price)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    // Retrieve the SKU of the product
    public function getSku()
    {
        return $this->sku;
    }

    // Set the SKU of the product
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    // Retrieve the name of the product
    public function getName()
    {
        return $this->name;
    }

    // Set the name of the product
    public function setName($name)
    {
        $this->name = $name;
    }

    // Retrieve the price of the product
    public function getPrice()
    {
        return $this->price;
    }

    // Set the price of the product
    public function setPrice($price)
    {
        $this->price = $price;
    }

    // Abstract method to save product details; implementation required in subclasses
    abstract public function save();

    // Abstract method to display product details; implementation required in subclasses
    abstract public function display();
}
