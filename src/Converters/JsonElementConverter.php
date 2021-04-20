<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\Schema\Elements\JsonElement;

class JsonElementConverter extends AbstractElementConverter
{
    public function __construct(
        JsonElement $element,
        OpenApiConvertFactory $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        $requiredKeys = [];
        foreach ($this->element->getSchema() as $key => $item) {
            if ($item->isRequired()) {
                $requiredKeys[] = $key;
            }
        }
        $data['required'] = $requiredKeys;

        if ($this->factory->isUseRefs() && $this->element->getSchemaSourceClass()) {
            $data['$ref'] = $this->factory->createRef($this->element->getSchemaSourceClass());
        } else {
            $properties = [];
            foreach ($this->element->getSchema() as $key => $item) {
                $properties[$key] = $this->factory->convert($item);
                ///**
                // * to avoid infinite loops, $refs should be generated in some cases!
                // */
                //if ($this->element->getSchemaGatheredFromClass()) {
                //    $properties[$key] = [
                //        '$ref' => $this->factory->createRef($this->element->getSchemaGatheredFromClass()),
                //    ];
                //} else {
                //    $properties[$key] = $this->factory->convert($item);
                //}
            }
            $data['properties'] = $properties;
        }

        if ($this->element->getExtraElementSchema()) {
            $data['additionalProperties'] = $this->factory->convert($this->element->getExtraElementSchema());
        }

        return $data;
    }
}
