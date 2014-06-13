<?php

namespace ReactJS\Renderer;

use ReactJS\RuntimeFragmentProvider\SynchronousRequireProvider;

/**
 * @package ReactJS\Renderer
 * @requires extension v8js
 */
class V8JsRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ReactJS\Renderer\V8JsRenderer
     */
    protected $renderer;

    public function setUp()
    {
        $this->renderer = new V8JsRenderer(
            [__DIR__ . '/../../fixtures/bundle.js']
        );
    }

    public function testRenderMountableComponent()
    {
        $markup = $this->renderer->renderMountableComponent('./TestComponent');

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

    public function testRenderStaticComponent()
    {
        $markup = $this->renderer->renderStaticComponent('./TestComponent');

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