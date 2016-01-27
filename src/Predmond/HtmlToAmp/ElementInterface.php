<?php

namespace Predmond\HtmlToAmp;

interface ElementInterface
{
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
     * @return bool
     */
    public function hasChildren();

    /**
     * @return ElementInterface[]
     */
    public function getChildren();
}
