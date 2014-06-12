<?php

namespace ReactJS;

/**
 * @package ReactJS
 */
interface GenerateRenderRuntimeInterface
{
    /**
     * @param $componentPath
     * @param array|void $props
     * @param string $renderedMountableComponentMarkup
     * @return string
     */
    public function mountedComponentMarkup($componentPath, $props = null, $renderedMountableComponentMarkup = '');

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function mountableComponentJS($componentPath, $props = null);

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function staticComponentJS($componentPath, $props = null);
}