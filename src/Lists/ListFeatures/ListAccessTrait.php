<?php
declare(strict_types=1);
/*
 * Copyright (C) 2022 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Lists\ListFeatures;

/**
 * @property array $array
 */
trait ListAccessTrait
{
    /**
     * {@inheritdoc}
     */
    public function has(int $index): bool
    {
        return array_key_exists($index, $this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $index)
    {
        return $this->array[$index] ?? null;
    }

    /**
     * @param int $index
     * @param mixed $element
     * @return void
     */
    public function set(int $index, $element): void
    {
        $this->array[$index] = $element;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function last()
    {
        $item = end($this->array);
        reset($this->array);
        return $item === false ? null: $item;
    }
}
