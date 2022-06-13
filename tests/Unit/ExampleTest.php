<?php


it('example', function () {
    expect(true)->toBeTrue();
});

it('is default template a string', function () {
     expect(\AksService\DocumentWrapper\Document::make()->getTemplate())->toBeString();
});
