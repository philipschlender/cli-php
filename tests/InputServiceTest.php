<?php

namespace Tests;

use Cli\Enumerations\ArgumentType;
use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\Argument;
use Cli\Models\Option;
use Cli\Services\InputService;
use Cli\Services\InputServiceInterface;

class InputServiceTest extends TestCase
{
    protected InputServiceInterface $inputService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inputService = new InputService();
    }

    public function testInitialize(): void
    {
        $this->assertEmpty($this->inputService->getOptions());
        $this->assertEmpty($this->inputService->getArguments());

        $options = [
            new Option('first-option', 'f', OptionType::RequiredValue),
            new Option('second-option', 's', OptionType::OptionalValue),
            new Option('third-option', 't', OptionType::NoValue),
        ];

        $arguments = [
            new Argument('first-argument', ArgumentType::Required),
            new Argument('second-argument', ArgumentType::Optional),
        ];

        $this->inputService->initialize($options, $arguments);

        $this->assertEquals($options, $this->inputService->getOptions());
        $this->assertEquals($arguments, $this->inputService->getArguments());
    }

    public function testParse(): void
    {
        $this->markTestSkipped();
    }

    public function testGetOptions(): void
    {
        $this->assertEmpty($this->inputService->getOptions());

        $options = [
            new Option('first-option', 'f', OptionType::RequiredValue),
            new Option('second-option', 's', OptionType::OptionalValue),
            new Option('third-option', 't', OptionType::NoValue),
        ];

        $this->inputService->initialize($options, []);

        $this->assertEquals($options, $this->inputService->getOptions());
    }

    public function testGetOption(): void
    {
        $options = [
            new Option('first-option', 'f', OptionType::RequiredValue),
        ];

        $this->inputService->initialize($options, []);

        $this->assertEquals($options[0], $this->inputService->getOption($options[0]->getName()));
    }

    public function testGetOptionFailedToFindOption(): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('Failed to find the option.');

        $this->inputService->getOption($this->fakerService->getDataTypeGenerator()->randomString());
    }

    public function testGetArguments(): void
    {
        $this->assertEmpty($this->inputService->getArguments());

        $arguments = [
            new Argument('first-argument', ArgumentType::Required),
            new Argument('second-argument', ArgumentType::Optional),
        ];

        $this->inputService->initialize([], $arguments);

        $this->assertEquals($arguments, $this->inputService->getArguments());
    }

    public function testGetArgument(): void
    {
        $arguments = [
            new Argument('first-argument', ArgumentType::Required),
        ];

        $this->inputService->initialize([], $arguments);

        $this->assertEquals($arguments[0], $this->inputService->getArgument($arguments[0]->getName()));
    }

    public function testGetArgumentFailedToFindArgument(): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('Failed to find the argument.');

        $this->inputService->getArgument($this->fakerService->getDataTypeGenerator()->randomString());
    }
}
