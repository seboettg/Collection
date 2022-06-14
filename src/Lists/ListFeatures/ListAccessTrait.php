<?php

namespace Seboettg\Collection\Lists\ListFeatures;

/**
 * @property array $array
 */
trait ListAccessTrait
{
    /**
     * {@inheritdoc}
     */
    public function get(int $index)
    {
        return $this->array[$index] ?? null;
    }

    /**
     * @param $key
     * @param $element
     * @return void
     */
    public function set($key, $element): void
    {
        $this->array[$key] = $element;
    }

    /**
     * Returns the first element
     * @return mixed
     */
    public function first()
    {
        if (empty($this->array)) {
            return null;
        }
        reset($this->array);
        return $this->array[key($this->array)];
    }

    /**
     * Returns the last element
     * @return mixed
     */
    public function last()
    {
        $item = end($this->array);
        reset($this->array);
        return $item;
    }
}
