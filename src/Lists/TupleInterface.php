<?php

namespace Seboettg\Collection\Lists;

interface TupleInterface
{
    /**
     * @return mixed
     */
    public function getFirst();

    /**
     * @return mixed
     */
    public function getSecond();
}