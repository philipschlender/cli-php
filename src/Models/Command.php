<?php

namespace Cli\Models;

use Cli\Exceptions\CliException;
use Cli\Services\InputServiceInterface;

abstract class Command implements CommandInterface
{
    public function __construct(
        protected InputServiceInterface $inputService,
    ) {
    }

    /**
     * @throws CliException
     */
    public function handle(): int
    {
        $this->inputService->parse();

        return $this->execute();
    }

    /**
     * @throws CliException
     */
    abstract protected function execute(): int;
}
