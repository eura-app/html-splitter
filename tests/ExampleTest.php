<?php

use David\HtmlSplitter\HtmlSplitter;

it('does not split the html elements but keeps the html', function () {
    $text = '<h2>A Pastorala Ode to the Netherlands w O land of tulips and zephyrs mild</h2>
            <div><br></div>
            <div>O land of tulips and zephyrs mild, Where doth the rivers’ gentle flow abide, Through pastures green and valleys undefiled, ‘Neath Heaven’s vault where golden light doth glide.</div>
            <div><br></div>
            <div>In thee, the vernal bloom doth ever sing, Of liberty and fortune’s favor bright, From Friesland’s fields to Zeeland’s tranquil spring, Each province shines with Nature’s pure delight.</div>
            <div><br></div>
            <div>The stately windmills turn with noble grace, Their sails as sentinels of time’s grand march, While dikes, steadfast, hold Neptune’s wild embrace, A bulwark firm against his watery arch.</div>
            <div><br></div>
            <div>And in thy cities, vibrant and serene, Art’s splendor and the muse of trade conspire, To craft a realm where history hath been, A testament to dreams that never tire.</div>
            <div><br></div>
            <div>So let us raise our hearts in joyous praise, To thee,</div>
            <ul><li>So let us raise our hearts in joyous praise</li><li>For in thy fertile bosom’s warm embrace</li><li>So let us raise our hearts in joyous praise</li><li>For in thy fertile bosom’s warm embrace</li></ul>
            <div>O Netherlands, our pride and cheer, For in thy fertile bosom’s warm embrace, Resides a heritage we hold most dear. </div>';

    $htmlSplitter = new HtmlSplitter;
    $maxLength = 5;
    $array = $htmlSplitter->generate($text, $maxLength, 20);

    expect($array[0]['text'])->toEqual('<h2>A Pastorala Ode<br/>to the Netherlands w<br/>O land of tulips and<br/>zephyrs mild</h2>');
    expect($array[1]['text'])->toEqual('O land of tulips and<br/>zephyrs mild, Where<br/>doth the rivers’<br/>gentle flow abide,<br/>Through pastures');
    expect($array[2]['text'])->toEqual('green and valleys<br/>undefiled, ‘Neath<br/>Heaven’s vault<br/>where golden light<br/>doth glide.');
});
