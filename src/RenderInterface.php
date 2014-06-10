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
    public function renderComponentToString($componentPath, $props = null);
    
    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderComponentToStaticMarkup($componentPath, $props = null);
}