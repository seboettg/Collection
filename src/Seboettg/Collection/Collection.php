<?php

/*
 * Copyright (C) 2016 Sebastian Böttger <seboettg@gmail.com>
 * You may use, distribute and modify this code under the
 * terms of the MIT license.
 *
 * You should have received a copy of the MIT license with
 * this file. If not, please visit: https://opensource.org/licenses/mit-license.php
 */

namespace Seboettg\Collection;

/**
 * Interface Collection
 * @package Seboettg\Collection
 * 
 * @author <seboettg@gmail.com>
 */
interface Collection extends \ArrayAccess, \IteratorAggregate, \Countable, ToArrayInterface
{

}
