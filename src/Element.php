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

    public function getNode()
    {
        return $this->node;
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

    public function setAttribute($attributeName, $attributeValue)
    {
        if ($this->node instanceof DOMElement) {
            $this->node->setAttribute($attributeName, $attributeValue);
        }
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
     * @return ElementInterface|null
     */
    public function getParent()
    {
        return new static($this->node->parentNode) ?: null;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->node->hasChildNodes();
    }

    /**
     * Append an Element as a child of the current element
     *
     * @param  Element $element The element to append as a child
     * @return null
     */
    public function appendChild(Element $element)
    {
        $this->getNode()->appendChild($element->getNode());
    }

    /**
     * @param string $elementName
     * @param array $attributes
     * @return static
     */
    public function createWritableElement($elementName, array $attributes = [])
    {
        $element = $this->node->ownerDocument->createElement($elementName);

        if ($element instanceof \DOMElement) {
            foreach ($attributes as $attribute => $value) {
                $element->setAttribute($attribute, $value);
            }
        }

        return new static($element);
    }

    /**
     * Replace current element with a new DOMNode
     *
     * @param $node
     * @return DOMNode|bool
     */
    public function replaceWith(ElementInterface $element)
    {
        if ($this->node->parentNode !== null) {
            return $this->node->parentNode->replaceChild(
                $element->getNode(),
                $this->getNode()
            );
        }

        return false;
    }

    /**
     * Remove this element from the referenced DOMDocument
     *
     * @return mixed
     */
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

        if ($this->node->childNodes->length > 0) {
            /** @var \DOMNode $node */
            foreach ($this->node->childNodes as $node) {
                $children[] = new static($node);
            }
        }

        return $children;
    }
}
