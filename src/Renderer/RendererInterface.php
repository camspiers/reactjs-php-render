<?php

namespace ReactJS\Renderer;

/**
 * @package ReactJS
 */
interface RendererInterface
{
    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountableComponent($componentPath, $props = null);
    
    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderStaticComponent($componentPath, $props = null);
}