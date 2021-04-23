<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\OpenApi3\OpenApi3Generator;
use CodexSoft\Transmission\Schema\Elements\BasicElement;

class BasicElementConverter extends AbstractElementConverter
{
    public function __construct(
        BasicElement $element,
        OpenApi3Generator $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();
        if ($this->element->getExample() !== BasicElement::UNDEFINED) {
            $data['example'] = $this->element->getExample();
        }

        if ($this->element->hasDefaultValue()) {
            $data['default'] = $this->element->getDefaultValue();
        }

        return $data;
    }
}
