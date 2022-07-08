<?php

namespace Seboettg\Collection\Lists\ListFeatures;

use Seboettg\Collection\Lists\ListInterface;
use function Seboettg\Collection\Lists\emptyList;
use function Seboettg\Collection\Lists\listOf;

trait ListOperationsTrait
{



    public function plus(iterable $other): ListInterface
    {
        $list = listOf(...$this->array);
        $list->addAll($other);
        return $list;
    }

    /**
     * @inheritDoc
     */
    public function minus(iterable $values): ListInterface
    {
        $valuesList = emptyList();
        if (!$values instanceof ListInterface && is_array($values)) {
            $valuesList->setArray($values);
        } else {
            $valuesList = $values;
        }
        $newInstance = emptyList();
        foreach ($this->array as $value) {
            if (!$valuesList->contains($value)) {
                $newInstance->add($value);
            }
        }
        return $newInstance;
    }

    /**
     * @inheritDoc
     */
    public function intersect(ListInterface $list): ListInterface
    {
        $result = emptyList();
        foreach ($list as $item) {
            if ($this->contains($item)) {
                $result->add($item);
            }
        }
        return $result;
    }
}