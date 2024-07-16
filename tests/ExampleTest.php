<?php

use David\HtmlSplitter\HtmlSplitter;



it('can test', function () {
    expect(true)->toBeTrue();
});

it('it is a array', function () {
    $htmlSplitter = new HtmlSplitter();

    expect($htmlSplitter->fromHtml('Hello, David!'))->toBeArray();
});


function checkArrayItemsLength(array $array, int $maxLength): bool
{
    foreach ($array as $item) {
        if (strlen($item) > $maxLength) {
            return false;
        }
    }
    return true;
}

it('verifies all items in the array have a maximum length of 5 characters', function () {
    $htmlSplitter = new HtmlSplitter();
    $maxLength = 5;
    $array = $htmlSplitter->fromHtml(html: 'Hello, David!', max_characters_per_row: $maxLength);
    var_dump($array, 111);
    expect(checkArrayItemsLength($array, $maxLength))->toBeTrue();;
});

it('fails when an item in the array has more than 5 characters', function () {
    $htmlSplitter = new HtmlSplitter();
    $maxLength = 5;
    $array = $htmlSplitter->fromHtml('Hello, David!', $maxLength);

    expect(checkArrayItemsLength($array, $maxLength - 1))->toBeFalse();
});

it('does not split the html elements', function () {
    $htmlSplitter = new HtmlSplitter();
    $maxLength = 5;
    $array = $htmlSplitter->fromHtml('<h1>Hello</h1> David!', $maxLength);
    var_dump($array);
    expect(checkArrayItemsLength($array, $maxLength))->toBeTrue();
});
