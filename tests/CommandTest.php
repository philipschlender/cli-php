<?php

namespace Tests;

use Cli\Enumerations\ArgumentType;
use Cli\Enumerations\OptionType;
use Cli\Exceptions\CliException;
use Cli\Models\Argument;
use Cli\Models\Command;
use Cli\Models\CommandInterface;
use Cli\Models\Option;
use Cli\Services\InputServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;

class CommandTest extends TestCase
{
    protected MockObject&InputServiceInterface $inputService;

    protected CommandInterface $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inputService = $this->getMockBuilder(InputServiceInterface::class)->getMock();

        $this->command = new class($this->inputService) extends Command {
            /**
             * @throws CliException
             */
            public function __construct(
                InputServiceInterface $inputService,
            ) {
                $options = [
                    new Option('first-option', 'f', OptionType::RequiredValue),
                    new Option('second-option', 's', OptionType::OptionalValue),
                    new Option('third-option', 't', OptionType::NoValue),
                ];

                $arguments = [
                    new Argument('first-argument', ArgumentType::Required),
                    new Argument('second-argument', ArgumentType::Optional),
                ];

                parent::__construct($inputService, $options, $arguments);
            }

            protected function execute(): int
            {
                return 0;
            }
        };
    }

    public function testHandle(): void
    {
        $this->inputService->expects($this->once())->method('parse');

        $this->assertEquals(0, $this->command->handle());
    }
}
