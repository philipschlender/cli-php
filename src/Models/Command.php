<?php

namespace Cli\Models;

use Cli\Exceptions\CliException;
use Cli\Services\InputServiceInterface;

abstract class Command implements CommandInterface
{
    /**
     * @param array<int,OptionInterface>   $options
     * @param array<int,ArgumentInterface> $arguments
     */
    public function __construct(
        protected InputServiceInterface $inputService,
        array $options,
        array $arguments,
    ) {
        $this->inputService->initialize($options, $arguments);
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
