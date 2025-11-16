<?php

namespace Tests;

use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\Option;
use Cli\Models\OptionInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class OptionTest extends TestCase
{
    protected string $name;

    protected string $shortcut;

    protected OptionType $optionType;

    protected OptionInterface $option;

    protected function setUp(): void
    {
        parent::setUp();

        $this->name = $this->fakerService->getDataTypeGenerator()->randomString();

        $this->shortcut = $this->fakerService->getDataTypeGenerator()->randomString(1);

        $this->optionType = $this->fakerService->getArrayGenerator()->randomElement(OptionType::cases());

        $this->option = new Option($this->name, $this->shortcut, $this->optionType);
    }

    #[DataProvider('dataProviderConstructNameMustOnlyContainNumbersLettersAndDashes')]
    public function testConstructNameMustOnlyContainNumbersLettersAndDashes(string $name): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('The name must only contain numbers, letters and dashes.');

        new Option($name, $this->shortcut, $this->optionType);
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

        new Option('-', $this->shortcut, $this->optionType);
    }

    public function testConstructShortcutMustOnlyContainNumberOrLetter(): void
    {
        $this->expectException(CliException::class);
        $this->expectExceptionMessage('The shortcut must only contain a number or a letter.');

        new Option($this->name, '.', $this->optionType);
    }

    public function testGetName(): void
    {
        $this->assertEquals($this->name, $this->option->getName());
    }

    public function testGetShortcut(): void
    {
        $this->assertEquals($this->shortcut, $this->option->getShortcut());
    }

    public function testGetOptionType(): void
    {
        $this->assertEquals($this->optionType, $this->option->getOptionType());
    }

    public function testIsPassed(): void
    {
        $this->assertFalse($this->option->isPassed());

        $expectedIsPassed = $this->fakerService->getDataTypeGenerator()->randomBoolean();

        $this->option->setIsPassed($expectedIsPassed);

        $this->assertEquals($expectedIsPassed, $this->option->isPassed());
    }

    public function testGetValue(): void
    {
        $this->assertNull($this->option->getValue());

        $expectedValue = $this->fakerService->getArrayGenerator()->randomElement([
            $this->fakerService->getDataTypeGenerator()->randomString(),
            null,
        ]);

        $this->option->setValue($expectedValue);

        $this->assertEquals($expectedValue, $this->option->getValue());
    }
}
