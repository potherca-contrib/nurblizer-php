<?php

require __DIR__ . '/mustache.php';

if (array_key_exists('text', $_POST)) {
    require __DIR__ . '/nurble.php';

    $context = [
        'title' => 'Your Nurbled Text',
        'content' => nurble($_POST['text']),
        'footer.link' => '',
        'footer.title' => '<< Back',
    ];
} else {
    $context = [
        'title' => 'Nurblizer',
        'content' => false,
        'footer.link' => 'http://www.smbc-comics.com/?id=2779',
        'footer.title' => 'wtf?',
    ];
}

echo mustache(file_get_contents(__DIR__ . '/template.html.mustache'), $context);

/*EOF*/