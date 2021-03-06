<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\OpenApi3\OpenApi3Generator;
use CodexSoft\Transmission\Schema\Elements\NumberElement;

/** @property NumberElement $element */
class NumberElementConverter extends ScalarElementConverter
{
    public function __construct(
        NumberElement $element,
        OpenApi3Generator $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        if ($this->element->getMaxValue() !== null) {
            $data['maximum'] = $this->element->getMaxValue();
            $data['exclusiveMaximum'] = $this->element->isExclusiveMaximum();
        }

        if ($this->element->getMinValue() !== null) {
            $data['minimum'] = $this->element->getMinValue();
            $data['exclusiveMinimum'] = $this->element->isExclusiveMinimum();
        }

        return $data;
    }
}
