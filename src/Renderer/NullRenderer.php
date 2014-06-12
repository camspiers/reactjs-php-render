<?php

namespace ReactJS\Renderer;

use RuntimeException;

/**
 * @package ReactJS
 */
class NullRenderer implements RendererInterface
{
    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountableComponent($componentPath, $props = null)
    {
        return '';
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     * @throws \RuntimeException
     */
    public function renderStaticComponent($componentPath, $props = null)
    {
        throw new RuntimeException("renderStaticComponent not supported for null renderer");
    }
}