<?php

namespace Pepperit\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Entity
 */

class Product
{
    private $id;
    private $reference;
    private $name;
    private $description;
    private $quantity;
    private $price;
    private $color;
    private $category_id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($categoryId)
    {
        $this->category_id = $category_id;
    }
}
