<?php

namespace ReactJS;

class V8JsRendererTest extends \PHPUnit_Framework_TestCase
{
    protected $v8;
    
    public function setUp()
    {
        $this->v8 = new \V8Js;
    }

    public function testRenderComponentToString()
    {
        $r = new V8JsRenderer(
            $this->v8,
            [
                __DIR__ . '/../fixtures/bundle.js'
            ]
        );
        
        $markup = $r->renderComponentToString('./TestComponent');
        
        $this->assertContains(
            'Some testing content',
            $markup
        );

        $this->assertContains(
            'data-reactid',
            $markup
        );

        $this->assertContains(
            'data-react-checksum',
            $markup
        );
    }
    
    public function testRenderComponentToStaticMarkup()
    {
        $r = new V8JsRenderer(
            $this->v8,
            [
                __DIR__ . '/../fixtures/bundle.js'
            ]
        );
        
        $markup = $r->renderComponentToStaticMarkup('./TestComponent');

        $this->assertContains(
            'Some testing content',
            $markup
        );

        $this->assertNotContains(
            'data-reactid',
            $markup
        );

        $this->assertNotContains(
            'data-react-checksum',
            $markup
        );
    }
}