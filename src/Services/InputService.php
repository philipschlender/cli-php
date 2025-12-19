<?php

namespace Cli\Services;

use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\ArgumentInterface;
use Cli\Models\OptionInterface;

class InputService implements InputServiceInterface
{
    /**
     * @var array<int,OptionInterface>
     */
    protected array $options;

    /**
     * @var array<int,ArgumentInterface>
     */
    protected array $arguments;

    public function __construct()
    {
        $this->options = [];
        $this->arguments = [];
    }

    /**
     * @param array<int,OptionInterface>   $options
     * @param array<int,ArgumentInterface> $arguments
     */
    public function initialize(array $options, array $arguments): static
    {
        $this->options = $options;
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @throws CliException
     */
    public function parse(): void
    {
        $shortOptions = '';
        $longOptions = [];

        /** @var array<int,string> $argumentValues */
        $argumentValues = $_SERVER['argv'];
        $argumentIndex = null;

        foreach ($this->options as $option) {
            if (is_string($option->getShortcut())) {
                $shortOptions .= match ($option->getOptionType()) {
                    OptionType::RequiredValue => sprintf('%s:', $option->getShortcut()),
                    OptionType::OptionalValue => sprintf('%s::', $option->getShortcut()),
                    OptionType::NoValue => $option->getShortcut(),
                };
            }

            $longOptions[] = match ($option->getOptionType()) {
                OptionType::RequiredValue => sprintf('%s:', $option->getName()),
                OptionType::OptionalValue => sprintf('%s::', $option->getName()),
                OptionType::NoValue => $option->getName(),
            };
        }

        $parsedOptions = getopt($shortOptions, $longOptions, $argumentIndex);

        foreach ($this->options as $option) {
            $value = $parsedOptions[$option->getName()] ?? null;

            if (is_null($value) && is_string($option->getShortcut())) {
                $value = $parsedOptions[$option->getShortcut()] ?? null;
            }

            if (is_string($value)) {
                $option->setIsPassed(true);
                $option->setValue($value);
            } elseif (is_bool($value)) {
                $option->setIsPassed(true);
            } elseif (is_array($value)) {
                $option->setIsPassed(true);
                $option->setValue(array_shift($value));
            }
        }

        $parsedArguments = array_slice($argumentValues, $argumentIndex);

        foreach ($this->arguments as $argument) {
            $value = array_shift($parsedArguments);

            if (is_string($value)) {
                $argument->setIsPassed(true);
                $argument->setValue($value);
            }
        }
    }

    /**
     * @return array<int,OptionInterface>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @throws CliException
     */
    public function getOption(string $name): OptionInterface
    {
        $option = array_find(
            $this->options,
            function (OptionInterface $option) use ($name) {
                return $option->getName() === $name;
            }
        );

        if (!$option instanceof OptionInterface) {
            throw new CliException('Failed to find the option.');
        }

        return $option;
    }

    /**
     * @return array<int,ArgumentInterface>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @throws CliException
     */
    public function getArgument(string $name): ArgumentInterface
    {
        $argument = array_find(
            $this->arguments,
            function (ArgumentInterface $argument) use ($name) {
                return $argument->getName() === $name;
            }
        );

        if (!$argument instanceof ArgumentInterface) {
            throw new CliException('Failed to find the argument.');
        }

        return $argument;
    }
}
