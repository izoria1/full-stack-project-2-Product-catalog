<?php

abstract class Product {
    // Properties
    protected $sku;
    protected $name;
    protected $price;

    // Constructor
    public function __construct($sku, $name, $price) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    // Getter and Setter for SKU
    public function getSku() {
        return $this->sku;
    }

    public function setSku($sku) {
        $this->sku = $sku;
    }

    // Getter and Setter for Name
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    // Getter and Setter for Price
    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    // Abstract methods that will be implemented by the subclasses
    abstract public function save();
    abstract public function display();
}



