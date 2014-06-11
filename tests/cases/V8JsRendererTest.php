<?php

namespace ReactJS;

class V8JsRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderComponentToString()
    {
        $r = new V8JsRenderer(
            new \V8Js,
            [
                __DIR__ . '/../fixtures/bundle.js'
            ]
        );
        
        $this->assertContains(
            'Some testing content',
            $r->renderComponentToString('./TestComponent')
        );
    }
}