<?php

namespace Cli\Models;

use Cli\Exceptions\CliException;

interface CommandInterface
{
    /**
     * @throws CliException
     */
    public function handle(): int;
}
