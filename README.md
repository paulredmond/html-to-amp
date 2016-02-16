# HTML to AMP HTML
A PHP Library to Convert HTML to [AMP HTML](https://www.ampproject.org/)

**This is a new library and not considered stable yet.**

### Documentation

See the [wiki](https://github.com/paulredmond/html-to-amp/wiki). Documentation is a WIP right now.

### Why?

I have a need to convert HTML content into valid AMP (a subset of HTML) format from disparate CMS systems in a (hopefully) somewhat consistent manner. Instead of hacking regular expressions and doing the same thing over and over again, I thought it would be useful to create a library to parse HTML and convert it into AMP HTML.

The library's **main purpose is parsing fragments of HTML content (an article body)** and converting it into valid AMP. At this point you could try to parse an entire HTML document, but that's not this library's sweet spot. Pull requests are welcomed of course if you find a need; [open an issue](https://github.com/paulredmond/html-to-amp/issues) and discuss your ideas before submitting a PR so I can understand your goals/needs and align them to this project.
 
### Design Goals
 
 * Make it simple, yet extensible
 * Convert and Replace Elements in a passive way
 * Allow client code to configure which conversions run
 * Allow client code to add custom conversions as needed
 
### Inspiration
 
 This Library was inspired by code design patterns found in the wonderful [thephpleague/html-to-markdown](https://github.com/thephpleague/html-to-markdown) PHP library. Generally, the objectives in this Library are similiar: parse HTML into another format&mdash;in this case a subset of HTML.
 
### Testing
 
[![Build Status](https://travis-ci.org/paulredmond/html-to-amp.svg?branch=master)](https://travis-ci.org/paulredmond/html-to-amp)

 ```
 $ vendor/bin/phpspec run
 ```
