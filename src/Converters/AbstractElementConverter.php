<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;

class AbstractElementConverter
{
    public function __construct(
        protected AbstractElement $element,
        protected OpenApiConvertFactory $factory
    )
    {
    }

    public function convert(): array
    {
        $data = [
            'description' => $this->element->getLabel(),
            'type' => $this->factory->targetTypeFromElementClass(\get_class($this->element)),
            'required' => $this->element->isRequired(),
            'nullable' => $this->element->isNullable(),
            'deprecated' => $this->element->isDeprecated(),
        ];

        return $data;
    }
}
