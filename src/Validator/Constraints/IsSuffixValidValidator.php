<?php

namespace EmanueleMinotto\DomainParserBundle\Validator\Constraints;

use Pdp\Parser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Constraint to check if the suffix is valid or not.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class IsSuffixValidValidator extends ConstraintValidator
{
    /**
     * Pdp Parser.
     */
    private \Pdp\Parser $parser;

    /**
     * Contructor used for the parser dependency.
     *
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        // allow check for URLs too
        if ($host = parse_url($value, PHP_URL_HOST)) {
            $value = $host;
        }

        if (!$value || $this->parser->isSuffixValid($value)) {
            return;
        }

        $suffix = $this->parser->getPublicSuffix($value) ?: $value;

        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('%suffix%', $suffix)
            ->addViolation();
    }
}
