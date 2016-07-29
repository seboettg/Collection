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
 * Comparable Interface for Elements as part of an <code>Model\ArrayList</code> (e.g. Post, Tag, Group) that should be
 * comparable and sortable.
 * @package Seboettg\Collection
 *
 * @author Sebastian Böttger <seboettg@gmail.com>
 */
interface Comparable {

    public function compare(Comparable $b);
}
