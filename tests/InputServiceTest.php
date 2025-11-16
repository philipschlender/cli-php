<?php

namespace Tests;

use Cli\Enumerations\ArgumentType;
use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\Argument;
use Cli\Models\ArgumentInterface;
use Cli\Models\Option;
use Cli\Models\OptionInterface;
use Cli\Services\InputService;
use Cli\Services\InputServiceInterface;

class InputServiceTest extends TestCase
{
    /**
     * @var array<int,OptionInterface>
     */
    protected array $options;

    /**
     * @var array<int,ArgumentInterface>
     */
    protected array $arguments;

    protected InputServiceInterface $inputService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->options = [
            new Option('first-option', 'f', OptionType::RequiredValue),
            new Option('second-option', 's', OptionType::OptionalValue),
            new Option('third-option', 't', OptionType::NoValue),
        ];

        $this->arguments = [
            new Argument('first-argument', ArgumentType::Required),
            new Argument('second-argument', ArgumentType::Optional),
        ];

        $this->inputService = new InputService($this->options, $this->arguments);
    }

    public function testParse(): void
    {
        $this->markTestSkipped();
    }

    public function testGetOptions(): void
    {
        $this->assertEquals($this->options, $this->inputService->getOptions());
    }

    public function testGetOption(): void
    {
        $this->assertEquals($this->options[0], $this->inputService->getOption($this->options[0]->getName()));
    }

    public function testGetOptionFailedToFindOption(): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('Failed to find the option.');

        $this->inputService->getOption($this->fakerService->getDataTypeGenerator()->randomString());
    }

    public function testGetArguments(): void
    {
        $this->assertEquals($this->arguments, $this->inputService->getArguments());
    }

    public function testGetArgument(): void
    {
        $this->assertEquals($this->arguments[0], $this->inputService->getArgument($this->arguments[0]->getName()));
    }

    public function testGetArgumentFailedToFindArgument(): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('Failed to find the argument.');

        $this->inputService->getArgument($this->fakerService->getDataTypeGenerator()->randomString());
    }
}
