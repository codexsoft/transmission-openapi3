<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\OpenApi3\OpenApi3Generator;
use CodexSoft\Transmission\Schema\Elements\StringElement;

/** @property StringElement $element */
class StringElementConverter extends ScalarElementConverter
{
    public function __construct(
        StringElement $element,
        OpenApi3Generator $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        $data['allowEmptyValue'] = !$this->element->isNotBlank();

        if ($this->element->getPattern() !== null) {
            $data['pattern'] = $this->element->getPattern();
        }

        if ($this->element->getMinLength() !== null) {
            $data['minLength'] = $this->element->getMinLength();
        }

        if ($this->element->getMaxLength() !== null) {
            $data['maxLength'] = $this->element->getMaxLength();
        }

        return $data;
    }
}
