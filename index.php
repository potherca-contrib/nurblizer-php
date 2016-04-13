<?php

require __DIR__ . '/mustache.php';

if (array_key_exists('text', $_POST)) {
    require __DIR__ . '/nurble.php';

    $context = [
        'title' => 'Your Nurbled Text',
        'content' => nurble($_POST['text']),
        'footer.link' => '',
        'footer.title' => '&lt;&lt; Back',
    ];
} else {
    $context = [
        'title' => 'Nurblizer',
        'content' => <<<HTML
        <form action="" method="post">
            <fieldset>
                <ul>
                    <li>
                        <label>Text to nurblize</label>
                        <textarea name="text"></textarea>
                    </li>
                    <li>
                        <input type="submit" value="Nurblize Away!">
                    </li>
                </ul>
            </fieldset>
        </form>
HTML
        ,
        'footer.link' => 'http://www.smbc-comics.com/?id=2779',
        'footer.title' => 'wtf?',
    ];
}

echo mustache(file_get_contents(__DIR__ . '/template.html'), $context);

/*EOF*/