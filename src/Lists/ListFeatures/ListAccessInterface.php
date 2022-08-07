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

interface ListAccessInterface
{
    /**
     * Returns true if the specified index exists
     * @param int $index
     * @return bool
     */
    public function has(int $index): bool;

    /**
     * Returns the element of the specified index
     * @param int $index
     * @return mixed|null
     */
    public function get(int $index);

    /**
     * Inserts or replaces the element at the specified position in this list with the specified element.
     *
     * @param int $key
     * @param $element
     * @return void
     */
    public function set(int $key, $element): void;

    /**
     * Returns the first element in this list
     * @return mixed|null
     */
    public function first();

    /**
     * Returns the last element in this list
     * @return mixed|null
     */
    public function last();

}
