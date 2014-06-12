<?php

require __DIR__ . '/../vendor/autoload.php';

$v8 = ReactJS\ReactFactory::createUsingV8(
	[__DIR__.'/../tests/fixtures/bundle.js']
);

echo $v8->renderAutoMountingComponent('./TestComponent'), PHP_EOL;
echo $v8->renderMountableComponent('./TestComponent'), PHP_EOL;
echo $v8->renderStaticComponent('./TestComponesnt'), PHP_EOL;