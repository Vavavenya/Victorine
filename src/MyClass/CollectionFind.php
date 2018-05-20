<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 20.05.2018
 * Time: 12:08
 */

namespace App\MyClass;


class CollectionFind
{
    private $object;

    private $collection;

    public function Find()
    {
        foreach ($this->collectio as $sumobj){
            if ($sumobj->getId()==$this->object) {
                break;
            }
        }
        return $sumobj;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
    }
}