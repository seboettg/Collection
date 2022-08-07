<?php
/*
 * Copyright (C) 2022 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection;

use Seboettg\Collection\Map\MapInterface;
use Seboettg\Collection\Map\MapTrait;

class Map implements MapInterface
{
    use MapTrait;

    /**
     * @var array $array
     */
    protected $array = [];
}
