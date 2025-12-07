<?php

use Cli\Enumerations\ArgumentType;
use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\Argument;
use Cli\Models\ArgumentInterface;
use Cli\Models\Command;
use Cli\Models\Option;
use Cli\Models\OptionInterface;
use Cli\Services\InputService;
use Json\Exceptions\JsonException;
use Json\Services\JsonService;
use Json\Services\JsonServiceInterface;

require_once realpath(sprintf('%s/../vendor/autoload.php', __DIR__));

$command = new class extends Command {
    protected JsonServiceInterface $jsonService;

    /**
     * @throws CliException
     */
    public function __construct()
    {
        $this->jsonService = new JsonService();

        $options = [
            new Option('first-option', 'f', OptionType::RequiredValue),
            new Option('second-option', 's', OptionType::OptionalValue),
            new Option('third-option', 't', OptionType::NoValue),
        ];

        $arguments = [
            new Argument('first-argument', ArgumentType::Required),
            new Argument('second-argument', ArgumentType::Optional),
        ];

        parent::__construct(new InputService($options, $arguments));
    }

    /**
     * @throws JsonException
     */
    protected function execute(): int
    {
        echo $this->serializeOutput(
            $this->inputService->getOptions(),
            $this->inputService->getArguments()
        );

        return 0;
    }

    /**
     * @param array<int,OptionInterface>   $options
     * @param array<int,ArgumentInterface> $arguments
     *
     * @throws JsonException
     */
    protected function serializeOutput(array $options, array $arguments): string
    {
        $lines = [
            $this->optionsToJson($options),
            $this->argumentsToJson($arguments),
        ];

        return implode("\n", $lines);
    }

    /**
     * @param array<int,OptionInterface> $options
     *
     * @throws JsonException
     */
    protected function optionsToJson(array $options): string
    {
        $data = [];

        foreach ($options as $option) {
            $data[] = [
                'name' => $option->getName(),
                'shortcut' => $option->getShortcut(),
                'optionType' => $option->getOptionType()->value,
                'isPassed' => $option->isPassed(),
                'value' => $option->getValue(),
            ];
        }

        return $this->jsonService->arrayToJson($data);
    }

    /**
     * @param array<int,ArgumentInterface> $arguments
     *
     * @throws JsonException
     */
    protected function argumentsToJson(array $arguments): string
    {
        $data = [];

        foreach ($arguments as $argument) {
            $data[] = [
                'name' => $argument->getName(),
                'argumentType' => $argument->getArgumentType()->value,
                'isPassed' => $argument->isPassed(),
                'value' => $argument->getValue(),
            ];
        }

        return $this->jsonService->arrayToJson($data);
    }
};

exit($command->handle());
