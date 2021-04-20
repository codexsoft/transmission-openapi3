<?php


namespace CodexSoft\Transmission\OpenApi3\Converters;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\BoolElement;
use CodexSoft\Transmission\Schema\Elements\CollectionElement;
use CodexSoft\Transmission\Schema\Elements\IntegerElement;
use CodexSoft\Transmission\Schema\Elements\JsonElement;
use CodexSoft\Transmission\Schema\Elements\NumberElement;
use CodexSoft\Transmission\Schema\Elements\ScalarElement;
use CodexSoft\Transmission\Schema\Elements\StringElement;

class OpenApiConvertFactory
{
    protected array $references = [];
    protected bool $useRefs = true;

    /**
     * @param string $class
     *
     * @return string
     * @throws \ReflectionException
     */
    public function createRef(string $class): string
    {
        $reflection = new \ReflectionClass($class);
        return '#/components/schemas/'.$reflection->getShortName();
    }

    protected function findConverterClass(string $elementClass): string
    {
        $knownConverters = [
            CollectionElement::class => CollectionElementConverter::class,
            JsonElement::class => JsonElementConverter::class,
            NumberElement::class => NumberElementConverter::class,
            ScalarElement::class => ScalarElementConverter::class,
            StringElement::class => StringElementConverter::class,
        ];

        if (\array_key_exists($elementClass, $knownConverters)) {
            return $knownConverters[$elementClass];
        }

        foreach (\class_parents($elementClass) as $classParent) {
            if (\array_key_exists($classParent, $knownConverters)) {
                return $knownConverters[$classParent];
            }
        }

        return AbstractElementConverter::class;
    }

    public function convert(AbstractElement $element): array
    {
        $converterClass = $this->findConverterClass(\get_class($element));
        /** @var AbstractElementConverter $converter */
        $converter = new $converterClass($element, $this);
        return $converter->convert();
    }

    public function targetTypeFromElementClass(string $elementClass): string
    {
        $knownTypes = [
            BoolElement::class => 'boolean',
            CollectionElement::class => 'array',
            IntegerElement::class => 'integer',
            JsonElement::class => 'object',
            NumberElement::class => 'number',
            StringElement::class => 'string',
        ];

        if (\array_key_exists($elementClass, $knownTypes)) {
            return $knownTypes[$elementClass];
        }

        foreach (\class_parents($elementClass) as $classParent) {
            if (\array_key_exists($classParent, $knownTypes)) {
                return $knownTypes[$classParent];
            }
        }

        return 'mixed';
    }

    /**
     * @return bool
     */
    public function isUseRefs(): bool
    {
        return $this->useRefs;
    }

    /**
     * @return array
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * @param bool $useRefs
     *
     * @return OpenApiConvertFactory
     */
    public function setUseRefs(bool $useRefs): OpenApiConvertFactory
    {
        $this->useRefs = $useRefs;
        return $this;
}
}
