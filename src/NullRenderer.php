<?php

namespace ReactJS;

use RuntimeException;

/**
 * @package ReactJS
 */
class NullRenderer implements RenderInterface
{
    public function __construct()
    {
        $this->$generate = new SyncRequireGenerator();
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountedComponent($componentPath, $props = null)
    {
        return $this->$generate->mountedComponentMarkup(
            $componentPath,
            $props,
            $this->renderMountableComponent($componentPath, $props)
        );
    }

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