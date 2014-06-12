<?php

namespace ReactJS;

/**
 * @package ReactJS
 */
interface RenderInterface
{
    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountedComponent($componentPath, $props = null);

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