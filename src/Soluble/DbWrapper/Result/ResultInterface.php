<?php

namespace Soluble\DbWrapper\Result;

use Countable;
use Traversable;
use Iterator;
use ArrayAccess;

interface ResultInterface extends Countable, Traversable, Iterator, ArrayAccess
{
    /**
     * Append a row to the resultset.
     *
     * @param array $row
     */
    public function append(array $row);
}
