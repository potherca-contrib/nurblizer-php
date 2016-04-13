<?php

/**
 * @param string $template
 * @param array $context
 *
 * @return string
 */
function mustache($template, array $context = [])
{
    return preg_replace_callback('/{{\s?([a-zA-Z\-\_\.]+)\s?}}/m', function (array $matches) use ($context) {
        $result = $matches[1];
        if (array_key_exists($result, $context)) {
            $result = $context[$result];
        }
        return $result;
    }, $template);
}

/*EOF*/