<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\ScalarElement;

class ScalarElementConverter extends AbstractElementConverter
{
    public function __construct(
        ScalarElement $element,
        OpenApiConvertFactory $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        if ($this->element->getChoicesSourceArray()) {
            $data['enum'] = $this->element->getChoicesSourceArray();

            if ($this->element->getExample() === AbstractElement::UNDEFINED || !\in_array($this->element->getExample(), $this->element->getChoicesSourceArray(), true)) {
                $data['example'] = \array_values($this->element->getChoicesSourceArray())[0];
            }
        }

        return $data;
    }
}
