<?php
/*
 * Copyright (C) 2018 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection\Queue;

use Seboettg\Collection\CollectionInterface;

/**
 * Interface QueueInterface
 * @package Seboettg\Collection\Queue
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
interface QueueInterface extends CollectionInterface, \Countable
{

    /**
     * Adds an item to the queue
     * @param $item
     * @return mixed
     */
    public function enqueue($item);

    /**
     * Dequeues an item from the queue
     * @return mixed
     */
    public function dequeue();
}
