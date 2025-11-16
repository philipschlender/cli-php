<?php

namespace Cli\Models;

use Cli\Enumerations\ArgumentType;

interface ArgumentInterface
{
    public function getName(): string;

    public function getArgumentType(): ArgumentType;

    public function isPassed(): bool;

    public function setIsPassed(bool $isPassed): static;

    public function getValue(): ?string;

    public function setValue(?string $value): static;
}
