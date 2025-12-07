<?php

namespace Tests;

use Cli\Enumerations\ArgumentType;
use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\Argument;
use Cli\Models\ArgumentInterface;
use Cli\Models\Option;
use Cli\Models\OptionInterface;
use Json\Services\JsonService;
use Json\Services\JsonServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class ScriptTest extends TestCase
{
    protected JsonServiceInterface $jsonService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonService = new JsonService();
    }

    #[DataProvider('dataProviderHandle')]
    public function testHandle(
        string $optionsAndArguments,
        bool $expectedFirstOptionIsPassed,
        ?string $expectedFirstOptionValue,
        bool $expectedSecondOptionIsPassed,
        ?string $expectedSecondOptionValue,
        bool $expectedThirdOptionIsPassed,
        ?string $expectedThirdOptionValue,
        bool $expectedFirstArgumentIsPassed,
        ?string $expectedFirstArgumentValue,
        bool $expectedSecondArgumentIsPassed,
        ?string $expectedSecondArgumentValue,
    ): void {
        [$resultCode, $output] = $this->executeScript($optionsAndArguments);

        [$options, $arguments] = $this->unserializeOutput($output);

        $this->assertEquals(0, $resultCode);

        $this->assertEquals('first-option', $options[0]->getName());
        $this->assertEquals($expectedFirstOptionIsPassed, $options[0]->isPassed());
        $this->assertEquals($expectedFirstOptionValue, $options[0]->getValue());

        $this->assertEquals('second-option', $options[1]->getName());
        $this->assertEquals($expectedSecondOptionIsPassed, $options[1]->isPassed());
        $this->assertEquals($expectedSecondOptionValue, $options[1]->getValue());

        $this->assertEquals('third-option', $options[2]->getName());
        $this->assertEquals($expectedThirdOptionIsPassed, $options[2]->isPassed());
        $this->assertEquals($expectedThirdOptionValue, $options[2]->getValue());

        $this->assertEquals('first-argument', $arguments[0]->getName());
        $this->assertEquals($expectedFirstArgumentIsPassed, $arguments[0]->isPassed());
        $this->assertEquals($expectedFirstArgumentValue, $arguments[0]->getValue());

        $this->assertEquals('second-argument', $arguments[1]->getName());
        $this->assertEquals($expectedSecondArgumentIsPassed, $arguments[1]->isPassed());
        $this->assertEquals($expectedSecondArgumentValue, $arguments[1]->getValue());
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    public static function dataProviderHandle(): array
    {
        return [
            [
                'optionsAndArguments' => '',
                'expectedFirstOptionIsPassed' => false,
                'expectedFirstOptionValue' => null,
                'expectedSecondOptionIsPassed' => false,
                'expectedSecondOptionValue' => null,
                'expectedThirdOptionIsPassed' => false,
                'expectedThirdOptionValue' => null,
                'expectedFirstArgumentIsPassed' => false,
                'expectedFirstArgumentValue' => null,
                'expectedSecondArgumentIsPassed' => false,
                'expectedSecondArgumentValue' => null,
            ],
            [
                'optionsAndArguments' => '--first-option first-option-value --second-option=second-option-value --third-option first-argument second-argument',
                'expectedFirstOptionIsPassed' => true,
                'expectedFirstOptionValue' => 'first-option-value',
                'expectedSecondOptionIsPassed' => true,
                'expectedSecondOptionValue' => 'second-option-value',
                'expectedThirdOptionIsPassed' => true,
                'expectedThirdOptionValue' => null,
                'expectedFirstArgumentIsPassed' => true,
                'expectedFirstArgumentValue' => 'first-argument',
                'expectedSecondArgumentIsPassed' => true,
                'expectedSecondArgumentValue' => 'second-argument',
            ],
            [
                'optionsAndArguments' => '--second-option',
                'expectedFirstOptionIsPassed' => false,
                'expectedFirstOptionValue' => null,
                'expectedSecondOptionIsPassed' => true,
                'expectedSecondOptionValue' => null,
                'expectedThirdOptionIsPassed' => false,
                'expectedThirdOptionValue' => null,
                'expectedFirstArgumentIsPassed' => false,
                'expectedFirstArgumentValue' => null,
                'expectedSecondArgumentIsPassed' => false,
                'expectedSecondArgumentValue' => null,
            ],
            [
                'optionsAndArguments' => '--first-option first-option-first-value --first-option first-option-second-value',
                'expectedFirstOptionIsPassed' => true,
                'expectedFirstOptionValue' => 'first-option-first-value',
                'expectedSecondOptionIsPassed' => false,
                'expectedSecondOptionValue' => null,
                'expectedThirdOptionIsPassed' => false,
                'expectedThirdOptionValue' => null,
                'expectedFirstArgumentIsPassed' => false,
                'expectedFirstArgumentValue' => null,
                'expectedSecondArgumentIsPassed' => false,
                'expectedSecondArgumentValue' => null,
            ],
        ];
    }

    /**
     * @return array{0:int,1:string}
     */
    protected function executeScript(string $optionsAndArguments): array
    {
        $script = realpath(sprintf('%s/script.php', __DIR__));

        if (!is_string($script)) {
            throw new CliException('Failed to get the canonicalized absolute pathname.');
        }

        $command = sprintf('php %s', $script);

        if (strlen($optionsAndArguments) > 0) {
            $command = sprintf('%s %s', $command, $optionsAndArguments);
        }

        $command = escapeshellcmd($command);

        $resultCode = null;

        ob_start();

        system($command, $resultCode);

        $output = ob_get_clean();

        if (!is_string($output)) {
            throw new CliException('Failed to get the output of the command.');
        }

        return [
            $resultCode,
            $output,
        ];
    }

    /**
     * @return array{0:array<int,OptionInterface>,1:array<int,ArgumentInterface>}
     */
    protected function unserializeOutput(string $output): array
    {
        $lines = explode("\n", $output);

        return [
            $this->jsonToOptions($lines[0]),
            $this->jsonToArguments($lines[1]),
        ];
    }

    /**
     * @return array<int,OptionInterface>
     */
    protected function jsonToOptions(string $json): array
    {
        $options = [];

        foreach ($this->jsonService->jsonToArray($json) as $data) {
            $option = new Option(
                $data['name'],
                $data['shortcut'],
                OptionType::from($data['optionType']),
            );

            $option->setIsPassed($data['isPassed'])
                ->setValue($data['value']);

            $options[] = $option;
        }

        return $options;
    }

    /**
     * @return array<int,ArgumentInterface>
     */
    protected function jsonToArguments(string $json): array
    {
        $arguments = [];

        foreach ($this->jsonService->jsonToArray($json) as $data) {
            $argument = new Argument(
                $data['name'],
                ArgumentType::from($data['argumentType']),
            );

            $argument->setIsPassed($data['isPassed'])
                ->setValue($data['value']);

            $arguments[] = $argument;
        }

        return $arguments;
    }
}
