<?php

namespace Pepperit\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Entity
 */

class Category
{
    private $id;
    private $name;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
}
