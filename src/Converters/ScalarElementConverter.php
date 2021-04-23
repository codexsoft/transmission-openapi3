<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\OpenApi3\OpenApi3Generator;
use CodexSoft\Transmission\Schema\Elements\BasicElement;
use CodexSoft\Transmission\Schema\Elements\ScalarElement;

/** @property ScalarElement $element */
class ScalarElementConverter extends BasicElementConverter
{
    public function __construct(
        ScalarElement $element,
        OpenApi3Generator $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        if ($this->element->getChoicesSourceArray()) {
            $data['enum'] = $this->element->getChoicesSourceArray();

            if ($this->element->getExample() === BasicElement::UNDEFINED || !\in_array($this->element->getExample(), $this->element->getChoicesSourceArray(), true)) {
                $data['example'] = \array_values($this->element->getChoicesSourceArray())[0];
            }
        }

        return $data;
    }
}
