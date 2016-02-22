<?php

namespace Predmond\HtmlToAmp;

interface ElementInterface
{
    public function getNode();

    /**
     * @return string
     */
    public function getTagName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * Get an attribute value from an element
     *
     * @param $attributeName
     * @return mixed
     */
    public function getAttribute($attributeName);

    /**
     * Set an attribute
     *
     * @param $attributeName
     * @param $attributeValue
     * @return null
     */
    public function setAttribute($attributeName, $attributeValue);

    /**
     * Get all node attributes
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Remove this element from the DOMDocument
     *
     * @return mixed
     */
    public function remove();

    /**
     * @return ElementInterface|null
     */
    public function getParent();

    /**
     * @return bool
     */
    public function hasChildren();

    /**
     * @return ElementInterface[]
     */
    public function getChildren();

    /**
     * Create a DOMNode Instance from the DOMDocument
     *
     * @param  string $elementName the name of the node
     * @param  array  $attributes  optional node attributes
     * @return ElementInterface
     */
    public function createWritableElement($elementName, array $attributes = []);

    public function replaceWith(ElementInterface $element);
}
