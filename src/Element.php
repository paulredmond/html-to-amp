<?php

namespace Predmond\HtmlToAmp;

use DOMNode;
use DOMElement;

class Element implements ElementInterface
{
    /**
     * @var DOMNode
     */
    private $node;

    public function __construct(DOMNode $node)
    {
        $this->node = $node;
    }

    public function getTagName()
    {
        return $this->node->nodeName;
    }

    public function getValue()
    {
        return $this->node->nodeValue;
    }

    public function getAttribute($attributeName)
    {
        if ($this->node instanceof DOMElement) {
            return $this->node->getAttribute($attributeName);
        }

        return '';
    }

    public function getAttributes()
    {
        $data = [];

        if (
            $this->node instanceof DOMElement
            && $this->node->hasAttributes()
        ) {
            foreach ($this->node->attributes as $attribute) {
                $data[$attribute->nodeName] = $attribute->nodeValue;
            }
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->node->hasChildNodes();
    }

    public function createWritableElement($elementName, array $attributes = [])
    {
        $element = $this->node->ownerDocument->createElement($elementName);

        if ($element instanceof \DOMElement) {
            foreach ($attributes as $attribute => $value) {
                $element->setAttribute($attribute, $value);
            }
        }

        return $element;
    }

    /**
     * Replace current element with a new DOMNode
     *
     * @param $node
     * @return DOMNode|bool
     */
    public function replaceWith($node)
    {
        if ($this->node->parentNode !== null) {
            return $this->node->parentNode->replaceChild($node,
                $this->node);
        }

        return false;
    }

    public function remove()
    {
        if ($this->node->parentNode) {
            return $this->node->parentNode->removeChild($this->node);
        }

        return false;
    }

    /**
     * @return ElementInterface[]
     */
    public function getChildren()
    {
        $children = [];

        /** @var \DOMNode $node */
        foreach ($this->node->childNodes as $node) {
            $children[] = new static($node);
        }

        return $children;
    }
}
