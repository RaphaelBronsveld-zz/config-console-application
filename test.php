<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */



class Cart {

    const priceButter = 1.00;
    const priceMilk = 3.00;
    const priceEggs = 4.00;

    protected $products = array();

    public function add($product, $quantity){
        $this->products[$product] = $quantity;
    }

    public function getQuantity($product){
        return isset($this->products[$product]) ? $this->products[$product] : FALSE;
    }

    public function getTotal($tax){
        $total = 0.00;

        $callback = function($quantity, $product) use ($tax, &$total){
            $pricePerItem = constant(__CLASS__."::price" . $product);

            $total += ($pricePerItem * $quantity) * ($tax + 1.0);
        };

        array_walk($this->products, $callback);

        return round($total, 2);
    }
}

$mycart = new Cart();

$mycart->add('Butter', 3);
$mycart->add('Milk', 1);
$mycart->add('Eggs', 2);

print $mycart->getTotal(0.19);

