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

    public function hasChildren()
    {
        return $this->node->hasChildNodes();
    }
}
