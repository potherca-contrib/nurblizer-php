<?php
/**
 * A minimal implementation of Mustache in PHP.
 *
 * Supports escaped and HTML Variables, Comments, Sections and Inverted Section.
 * Does not support Lists, Callbacks, Partials or Delimiter setting.
 *
 * @author Potherca <potherca@gmail.com>
 * @copyright 2016 Potherca
 * @license GPLv3+
 * @version 0.4.0
 *
 * @see https://gist.github.com/Potherca/94c0ba1449a2ee57aafddf961f3ebe5d/
 */

/**
 *
 * @param string $template
 * @param array $context
 *
 * @return string
 *
 * @license
 */
function mustache($template, array $context = [])
{
    $pattern = '@({{)(?<SECTION_TYPE>#|\^)(?P<SECTION_NAME>[a-zA-Z\-\_\.]+)(}})(?P<SECTION_CONTENT>.*?)({{/\3}})@sm';

    $template = preg_replace_callback($pattern, function (array $matches) use ($context) {
        $result = '';
        $key = $matches['SECTION_NAME'];

        if ($matches['SECTION_TYPE'] === '^') {
            // Inverted Section, will be rendered if the key doesn't exist, is false, or is an empty list
            if (!array_key_exists($key, $context) || empty($context[$key])) {
                $result = $matches['SECTION_CONTENT'];
            }
        } elseif ($matches['SECTION_TYPE'] === '#') {
            // Section, will be rendered if the key exists and has a value of true or is an non-empty list
            if (array_key_exists($key, $context) && !empty($context[$key])) {
                $result = $matches['SECTION_CONTENT'];
            }
        } else {
            throw new \UnexpectedValueException('Unknown section type in template.');
        }
        return $result;
    }, $template);

    $pattern = '/{{\!\s?(?P<COMMENT>.+)\s?}}|{{{\s?(?P<HTML_RAW>[a-zA-Z\-\_\.]+)\s?}}}|{{\s?(?P<HTML_ENCODE>[a-zA-Z\-\_\.]+)\s?}}/m';

    return preg_replace_callback($pattern, function (array $matches) use ($context) {
        if ($matches['COMMENT'] !== '') {
            $result = '';
        } elseif ($matches['HTML_RAW'] !== '') {
            $encode = false;
            $key = $matches['HTML_RAW'];
        } elseif ($matches['HTML_ENCODE'] !== '') {
            $encode = true;
            $key = $matches['HTML_ENCODE'];
        } else {
            throw new \UnexpectedValueException('Unknown tag type in template.');
        }

        if (!isset($result) && isset($key)) {
            $result = $key;
            if (array_key_exists($key, $context)) {
                $result = $context[$key];

                if (isset($encode) && (bool) $encode === true) {
                    $result = htmlentities($result, ENT_HTML5 | ENT_QUOTES);
                }
            }
        }
        return $result;
    }, $template);
}

/*EOF*/