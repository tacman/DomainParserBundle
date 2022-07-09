<?php

namespace EmanueleMinotto\DomainParserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint to check if the suffix is valid or not.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @Annotation
 */
class IsSuffixValid extends Constraint implements \Stringable
{
    /**
     * Constraint message.
     *
     * @var string
     */
    public $message = 'The suffix "%suffix%" is invalid.';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'is_suffix_valid';
    }

    public function __toString()
    {
        return $this->message;
    }
}
