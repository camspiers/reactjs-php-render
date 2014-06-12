<?php

namespace ReactJS\Renderer;

/**
 * @package ReactJS
 */
interface RendererInterface
{
    /**
     * Renders a component that is able to be mounted via JavaScript in the browser
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountableComponent($componentPath, $props = null);
    
    /**
     * Renders a static component unable to be mounted via JavaScript
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderStaticComponent($componentPath, $props = null);
}