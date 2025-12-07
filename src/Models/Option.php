<?php

namespace Cli\Models;

use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;

class Option implements OptionInterface
{
    protected string $name;

    protected ?string $shortcut;

    protected bool $isPassed;

    protected ?string $value;

    /**
     * @throws CliException
     */
    public function __construct(
        string $name,
        ?string $shortcut,
        protected OptionType $optionType,
    ) {
        if (1 !== preg_match('/^[0-9a-zA-Z\-]+$/', $name)) {
            throw new CliException('The name must only contain numbers, letters and dashes.');
        }

        if (str_starts_with($name, '-')) {
            throw new CliException('The name must not start with a dash.');
        }

        if (is_string($shortcut) && 1 !== preg_match('/^[0-9a-zA-Z]{1}$/', $shortcut)) {
            throw new CliException('The shortcut must only contain a number or a letter.');
        }

        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->isPassed = false;
        $this->value = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    public function getOptionType(): OptionType
    {
        return $this->optionType;
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
