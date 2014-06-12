<?php

namespace ReactJS;

use ReactJS\Renderer\NullRenderer;
use ReactJS\Renderer\RendererInterface;
use ReactJS\RuntimeFragmentProvider\ProviderInterface;
use ReactJS\RuntimeFragmentProvider\SynchronousRequireProvider;

/**
 * @package ReactJS
 */
class React implements RendererInterface
{
    /**
     * @var \ReactJS\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @var \ReactJS\RuntimeFragmentProvider\ProviderInterface
     */
    protected $fragmentProvider;

    /**
     * @param \ReactJS\Renderer\RendererInterface $renderer
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface $fragmentProvider
     */
    public function __construct(
        RendererInterface $renderer = null,
        ProviderInterface $fragmentProvider = null
    )
    {
        $this->renderer = $renderer ?: new NullRenderer();
        $this->fragmentProvider = $fragmentProvider ?: new SynchronousRequireProvider();
    }

    /**
     * @param $componentPath
     * @param null $props
     * @return string
     */
    public function renderAutoMountingComponent($componentPath, $props = null)
    {
        $containerId = uniqid();

        return sprintf(
            "<div id=\"%s\">%s</div><script>%s.renderComponent(%s(%s),document.getElementById(%s))</script>",
            $containerId,
            $this->renderer->renderMountableComponent($componentPath, $props),
            $this->fragmentProvider->getReact(),
            $this->fragmentProvider->getComponent($componentPath),
            json_encode($props),
            json_encode($containerId)
        );
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountableComponent($componentPath, $props = null)
    {
        return $this->renderer->renderMountableComponent($componentPath, $props);
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderStaticComponent($componentPath, $props = null)
    {
        return $this->renderer->renderStaticComponent($componentPath, $props);
    }
}