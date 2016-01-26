<?php

namespace Predmond\HtmlToAmp;

class AmpConverter
{
    public function convert($html)
    {
        return '<amp-img src="foo.jpg" width="300" height="300"></amp-img>';
    }
}
