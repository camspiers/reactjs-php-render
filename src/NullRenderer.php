<?php

namespace ReactJS;

use RuntimeException;

/**
 * @package ReactJS
 */
class NullRenderer implements RenderInterface
{
    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderComponentToString($componentPath, $props = null)
    {
        return '';
    }

    /**
     * @param $componentPath
     * @param null $props
     * @return string|void
     * @throws \RuntimeException
     */
    public function renderComponentToStaticMarkup($componentPath, $props = null)
    {
        throw new RuntimeException("renderComponentToStaticMarkup not supported for null renderer");
    }
}