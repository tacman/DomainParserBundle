<?php

namespace EmanueleMinotto\DomainParserBundle\Command;

use Pdp\Parser;
use Pdp\PublicSuffixListManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers EmanueleMinotto\DomainParserBundle\Command\ParseCommand
 */
class ParseCommandTest extends \PHPUnit\Framework\TestCase
{
    private ?\EmanueleMinotto\DomainParserBundle\Command\ParseCommand $command = null;

    private ?\Symfony\Component\Console\Tester\CommandTester $commandTester = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->command = new ParseCommand();
        $this->command->setContainer($this->getContainer());

        $application = new Application();
        $application->add($this->command);

        $this->commandTester = new CommandTester(
            $application->find('domain-parser:parse')
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
            ->with('pdp.parser')
            ->willReturn($this->getParser());

        return $container;
    }

    /**
     * Get testing Parser.
     *
     * @return Parser
     */
    private function getParser()
    {
        $manager = new PublicSuffixListManager();

        return new Parser($manager->getList());
    }

    /**
     * Test execution.
     */
    public function testExecute()
    {
        $exitCode = $this->commandTester->execute([
            'command' => $this->command->getName(),
            'url' => 'http://www.example.com/',
        ]);

        static::assertSame("Parsed: http://www.example.com/\n"
        ." scheme: http\n user: \n pass: \n host: www.example.com\n"
        ." subdomain: www\n registerableDomain: example.com\n"
        ." publicSuffix: com\n port: \n path: /\n query: \n"
        ." fragment: \n", $this->commandTester->getDisplay());
    }

    /**
     * Test execution with host only.
     */
    public function testExecuteWithHostOnly()
    {
        $exitCode = $this->commandTester->execute([
            'command' => $this->command->getName(),
            'url' => 'http://www.example.com/',
            '--host-only' => true,
        ]);

        static::assertSame("Parsed: www.example.com\n"
        ." subdomain: www\n registerableDomain: example.com\n"
        ." publicSuffix: com\n host: www.example.com\n", $this->commandTester->getDisplay());
    }
}
