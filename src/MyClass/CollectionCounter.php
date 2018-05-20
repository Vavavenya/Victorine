<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 20.05.2018
 * Time: 11:02
 */

namespace App\MyClass;


class CollectionCounter
{
    private $object;

    public function SizeObject()
    {
        $numplayers=0;
        foreach ($this->object->getLeaders() as $sfs) {
            $numplayers++;
        }
        return $numplayers;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }
}