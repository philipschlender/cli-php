<?php

namespace Cli\Services;

use Cli\Exceptions\CliException;
use Cli\Models\ArgumentInterface;
use Cli\Models\OptionInterface;

interface InputServiceInterface
{
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
