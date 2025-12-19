<?php

namespace Cli\Services;

use Cli\Exceptions\CliException;
use Cli\Models\ArgumentInterface;
use Cli\Models\OptionInterface;

interface InputServiceInterface
{
    /**
     * @param array<int,OptionInterface>   $options
     * @param array<int,ArgumentInterface> $arguments
     */
    public function initialize(array $options, array $arguments): static;

    /**
     * @throws CliException
     */
    public function parse(): void;

    /**
     * @return array<int,OptionInterface>
     */
    public function getOptions(): array;

    /**
     * @throws CliException
     */
    public function getOption(string $name): OptionInterface;

    /**
     * @return array<int,ArgumentInterface>
     */
    public function getArguments(): array;

    /**
     * @throws CliException
     */
    public function getArgument(string $name): ArgumentInterface;
}
