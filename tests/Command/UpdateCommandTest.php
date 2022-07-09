<?php

namespace EmanueleMinotto\DomainParserBundle\Command;

use Pdp\Parser;
use Pdp\PublicSuffixListManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers EmanueleMinotto\DomainParserBundle\Command\UpdateCommand
 */
class UpdateCommandTest extends \PHPUnit\Framework\TestCase
{
    private ?\EmanueleMinotto\DomainParserBundle\Command\UpdateCommand $command = null;

    private ?\Symfony\Component\Console\Tester\CommandTester $commandTester = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->command = new UpdateCommand();
        $this->command->setContainer($this->getContainer());

        $application = new Application();
        $application->add($this->command);

        $this->commandTester = new CommandTester(
            $application->find('domain-parser:update')
        );
    }

    /**
     * Get testing Container.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        $container = $this->createMock(ContainerInterface::class);

        $container
            ->method('get')
            ->with('pdp.public_suffix_list_manager')
            ->willReturn($this->getPublicSuffixListManager());

        return $container;
    }

    /**
     * Get testing Parser.
     *
     * @return Parser
     */
    private function getPublicSuffixListManager()
    {
        return new PublicSuffixListManager();
    }

    /**
     * Test execution.
     */
    public function testExecute()
    {
        $exitCode = $this->commandTester->execute([
            'command' => $this->command->getName(),
        ]);

        static::assertSame("Updating public suffix list... done\n", $this->commandTester->getDisplay());
    }
}
