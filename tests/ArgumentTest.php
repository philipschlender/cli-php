<?php

namespace Tests;

use Cli\Enumerations\ArgumentType;
use Cli\Exceptions\CliException;
use Cli\Models\Argument;
use Cli\Models\ArgumentInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class ArgumentTest extends TestCase
{
    protected string $name;

    protected ArgumentType $argumentType;

    protected ArgumentInterface $argument;

    protected function setUp(): void
    {
        parent::setUp();

        $this->name = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->argumentType = $this->fakerService->getArrayGenerator()->randomElement(ArgumentType::cases());

        $this->argument = new Argument($this->name, $this->argumentType);
    }

    #[DataProvider('dataProviderConstructNameMustOnlyContainNumbersLettersAndDashes')]
    public function testConstructNameMustOnlyContainNumbersLettersAndDashes(string $name): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('The name must only contain numbers, letters and dashes.');

        new Argument($name, $this->argumentType);
    }

    /**
     * @return array<int,array<string,string>>
     */
    public static function dataProviderConstructNameMustOnlyContainNumbersLettersAndDashes(): array
    {
        return [
            [
                'name' => '',
            ],
            [
                'name' => '.',
            ],
        ];
    }

    public function testConstructNameMustNotStartWithDash(): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('The name must not start with a dash.');

        new Argument('-', $this->argumentType);
    }

    public function testGetName(): void
    {
        $this->assertEquals($this->name, $this->argument->getName());
    }

    public function testGetArgumentType(): void
    {
        $this->assertEquals($this->argumentType, $this->argument->getArgumentType());
    }

    public function testIsPassed(): void
    {
        $this->assertFalse($this->argument->isPassed());

        $expectedIsPassed = $this->fakerService->getDataTypeGenerator()->randomBoolean();

        $this->argument->setIsPassed($expectedIsPassed);

        $this->assertEquals($expectedIsPassed, $this->argument->isPassed());
    }

    public function testGetValue(): void
    {
        $this->assertNull($this->argument->getValue());

        $expectedValue = $this->fakerService->getArrayGenerator()->randomElement([
            $this->fakerService->getDataTypeGenerator()->randomString(),
            null,
        ]);

        $this->argument->setValue($expectedValue);

        $this->assertEquals($expectedValue, $this->argument->getValue());
    }
}
