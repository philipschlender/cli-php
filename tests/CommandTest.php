<?php

namespace Tests;

use Cli\Models\Command;
use Cli\Models\CommandInterface;
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
