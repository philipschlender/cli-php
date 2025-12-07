<?php

namespace Cli\Models;

use Cli\Enumerations\OptionType;

interface OptionInterface
{
    public function getName(): string;

    public function getShortcut(): ?string;

    public function getOptionType(): OptionType;

    public function isPassed(): bool;

    public function setIsPassed(bool $isPassed): static;

    public function getValue(): ?string;

    public function setValue(?string $value): static;
}
