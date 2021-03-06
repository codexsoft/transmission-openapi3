<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\OpenApi3\OpenApi3Generator;
use CodexSoft\Transmission\Schema\Elements\CollectionElement;

/** @property CollectionElement $element */
class CollectionElementConverter extends BasicElementConverter
{
    public function __construct(
        CollectionElement $element,
        OpenApi3Generator $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        $data['uniqueItems'] = $this->element->isElementsMustBeUnique();

        if ($this->element->getMinCount() !== null) {
            $data['minItems'] = $this->element->getMinCount();
        }

        if ($this->element->getMaxCount() !== null) {
            $data['maxItems'] = $this->element->getMaxCount();
        }

        if ($this->element->getElementSchema() !== null) {
            if ($this->factory->isUseRefs() && $this->element->getSchemaSourceClass()) {
                $data['items'] = [
                    '$ref' => $this->factory->createRef($this->element->getSchemaSourceClass()),
                ];
            } else {
                $data['items'] = $this->factory->convert($this->element->getElementSchema());
            }

            // todo: 'allOf'?
        }

        return $data;
    }
}
