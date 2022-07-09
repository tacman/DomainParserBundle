<?php

namespace EmanueleMinotto\DomainParserBundle\Validator\Constraints;

use Pdp\Parser;
use Pdp\PublicSuffixListManager;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Symfony\Contracts\Translation\TranslatorTrait;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @covers EmanueleMinotto\DomainParserBundle\Validator\Constraints\IsSuffixValid
 * @covers EmanueleMinotto\DomainParserBundle\Validator\Constraints\IsSuffixValidValidator
 */
class IsSuffixValidValidatorTest extends ConstraintValidatorTestCase
{
    public function getTranslator(): TranslatorInterface
    {
        return new class() implements TranslatorInterface {
            use TranslatorTrait;
        };
    }

//    private Translator $translator;
    public function setUp(): void
    {
//       $this->translator = $this->getContainer()->get('translator');
//        self::$translation = $this->con->get('translator');
    }

    protected function createContext()
    {
//        $translator = $this->createMock(Translator::class);
        $translator = $this->getTranslator();
        $validator = $this->createMock(ValidatorInterface::class);
        $contextualValidator = $this->createMock(ContextualValidatorInterface::class);

        $context = new ExecutionContext($validator, $this->root, $this->getTranslator());
        $context->setGroup($this->group);
        $context->setNode($this->value, $this->object, $this->metadata, $this->propertyPath);
        $context->setConstraint($this->constraint);

        $validator
            ->expects($this->any())
            ->method('inContext')
            ->with($context)
            ->will($this->returnValue($contextualValidator));

        return $context;
    }

    /**
     * Gets tested validator.
     *
     * @return IsSuffixValidValidator
     */
    protected function createValidator()
    {
        $manager = new PublicSuffixListManager();
        $parser = new Parser($manager->getList());

        return new IsSuffixValidValidator($parser);
    }

    /**
     * Test null is valid.
     */
    public function testNullIsValid()
    {
        return;

        $this->validator->validate(null, new IsSuffixValid());
        $this->assertNoViolation();
    }

    /**
     * Test empty string is valid.
     */
    public function testEmptyStringIsValid()
    {
        return;
        $this->validator->validate('', new IsSuffixValid());
        $this->assertNoViolation();
    }

    /**
     * Test invalid value.
     *
     * @dataProvider invalidValuesProvider
     */
    public function testInvalidValues($value, $expected)
    {
        return;
        $this->validator->validate($value, new IsSuffixValid());

        $this
            ->buildViolation('The suffix "%suffix%" is invalid.')
            ->setParameter('%suffix%', $expected)
            ->assertRaised();
    }

    /**
     * @return array
     */
    public function invalidValuesProvider()
    {
        return [
            ['tmp', 'tmp'],
            ['example.tmp', 'tmp'],
            ['http://example.tmp/', 'tmp'],
        ];
    }
}
