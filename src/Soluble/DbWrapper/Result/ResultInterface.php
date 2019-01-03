<?php

declare(strict_types=1);

namespace Soluble\DbWrapper\Result;

use ArrayAccess;
use Countable;
use Iterator;
use Traversable;

interface ResultInterface extends Countable, Traversable, Iterator, ArrayAccess
{
    /**
     * Append a row to the resultset.
     *
     * @param array $row
     */
    public function append(array $row): void;
}
