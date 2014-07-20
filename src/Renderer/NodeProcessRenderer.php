<?php

namespace ReactJS\Renderer;

use Psr\Log\LoggerInterface;
use ReactJS\RuntimeFragmentProvider\ProviderInterface;

class NodeProcessRenderer implements RendererInterface
{
    use SourceFilesRendererTrait;
    use StdInProcessRendererTrait;
    use FragmentProvidingRendererTrait;

    /**
     * @param string $bin
     * @param array|\Traversable $sourceFiles
     * @param ProviderInterface $fragmentProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        $bin,
        $sourceFiles,
        ProviderInterface $fragmentProvider = null,
        LoggerInterface $logger = null
    )
    {
        $this->setBin($bin);
        $this->setSourceFiles($sourceFiles);
        if ($fragmentProvider) {
            $this->setFragmentProvider($fragmentProvider);
        }
        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * Renders a component that is able to be mounted via JavaScript in the browser
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountableComponent($componentPath, $props = null)
    {
        return $this->getOutput(
            $this->getJavaScript(
                'renderComponentToString',
                $componentPath,
                $props
            )
        );
    }

    /**
     * Renders a static component unable to be mounted via JavaScript
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderStaticComponent($componentPath, $props = null)
    {
        return $this->getOutput(
            $this->getJavaScript(
                'renderComponentToStaticMarkup',
                $componentPath,
                $props
            )
        );
    }

    /**
     * @param $reactFunction
     * @param $componentPath
     * @param null $props
     * @return string
     */
    protected function getJavaScript($reactFunction, $componentPath, $props = null)
    {
        $fragmentProvider = $this->getFragmentProvider();

        $javascript = [];

        foreach ($this->sourceFiles as $sourceFile) {
            $javascript[] = file_get_contents($sourceFile) . ";";
        }

        $javascript[] = sprintf(
            "console.log(%s.%s(%s(%s)));",
            $fragmentProvider->getReact(),
            $reactFunction,
            $fragmentProvider->getComponent($componentPath),
            json_encode($props)
        );
        
        return implode('', $javascript);
    }
}