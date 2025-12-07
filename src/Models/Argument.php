<?php

namespace Cli\Models;

use Cli\Enumerations\ArgumentType;
use Cli\Exceptions\CliException;

class Argument implements ArgumentInterface
{
    protected string $name;

    protected bool $isPassed;

    protected ?string $value;

    /**
     * @throws CliException
     */
    public function __construct(
        string $name,
        protected ArgumentType $argumentType,
    ) {
        if (1 !== preg_match('/^[0-9a-zA-Z\-]+$/', $name)) {
            throw new CliException('The name must only contain numbers, letters and dashes.');
        }

        if (str_starts_with($name, '-')) {
            throw new CliException('The name must not start with a dash.');
        }

        $this->name = $name;
        $this->isPassed = false;
        $this->value = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArgumentType(): ArgumentType
    {
        return $this->argumentType;
    }

    public function isPassed(): bool
    {
        return $this->isPassed;
    }

    public function setIsPassed(bool $isPassed): static
    {
        $this->isPassed = $isPassed;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
