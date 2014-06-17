<?php

namespace ReactJS\Renderer;

use Psr\Log\LoggerInterface;
use ReactJS\RuntimeFragmentProvider\SynchronousRequireProvider;
use V8Js;
use V8JsScriptException;
use V8JsTimeLimitException;
use V8JsMemoryLimitException;
use RuntimeException;
use ReactJS\RuntimeFragmentProvider\ProviderInterface;

/**
 * @package ReactJS
 */
class V8JsRenderer implements RendererInterface
{
    use SourceFilesRendererTrait;
    use LoggingRendererTrait;
    use FragmentProvidingRendererTrait;

    /**
     * @var \V8Js
     */
    protected $v8;

    /**
     * @var \ReactJS\RuntimeFragmentProvider\ProviderInterface
     */
    protected $fragmentProvider;

    /**
     * @param array|\Traversable $sourceFiles
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface
     * @param \V8Js $v8
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        $sourceFiles,
        ProviderInterface $fragmentProvider = null,
        V8Js $v8 = null,
        LoggerInterface $logger = null
    )
    {
        $this->setSourceFiles($sourceFiles);
        if ($fragmentProvider) {
            $this->setFragmentProvider($fragmentProvider);
        }
        if ($logger) {
            $this->setLogger($logger);
        }
        $this->v8 = $v8 ?: new V8Js;
    }

    /**
     * Renders a component that is able to be mounted via JavaScript in the browser
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderMountableComponent($componentPath, $props = null)
    {
        return $this->render("renderComponentToString", $componentPath, $props);
    }

    /**
     * Renders a static component unable to be mounted via JavaScript
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderStaticComponent($componentPath, $props = null)
    {
        return $this->render("renderComponentToStaticMarkup", $componentPath, $props);
    }

    /**
     * @param $reactFunction
     * @param $componentPath
     * @param null $props
     * @return string
     * @throws \Exception
     */
    private function render($reactFunction, $componentPath, $props = null)
    {
        $javascript = $this->getJavaScript($reactFunction, $componentPath, $props);

        $markup = '';

        try {
            ob_start();
            $markup = $this->v8->executeString($javascript);
            $loggableErrors = ob_get_clean();

            if ($loggableErrors) {
                $this->log(
                    "Errors in v8 javascript execution",
                    ["errors" => $loggableErrors]
                );
            }

            if (!is_string($markup)) {
                throw new RuntimeException("Value returned from v8 executeString isn't a string");
            }
        } catch (\Exception $e) {
            $this->log($e->getMessage());

            if ($reactFunction === 'renderComponentToStaticMarkup') {
                throw $e;
            }
        }

        return $markup;
    }

    /**
     * @param $reactFunction
     * @param $componentPath
     * @param $props
     * @return array
     */
    protected function getJavaScript($reactFunction, $componentPath, $props)
    {
        $fragmentProvider = $this->getFragmentProvider();

        $javascript = [];

        $javascript[] = "var console = { warn: print, error: print };";

        // Clear any module loaders
        if (method_exists($this->v8, 'setModuleLoader') && $fragmentProvider instanceof SynchronousRequireProvider) {
            $javascript[] = "function require() {}";
            $javascript[] = "var require = null;";
        }

        foreach ($this->sourceFiles as $sourceFile) {
            $javascript[] = file_get_contents($sourceFile) . ";";
        }

        $javascript[] = sprintf(
            "%s.%s(%s(%s));",
            $fragmentProvider->getReact(),
            $reactFunction,
            $fragmentProvider->getComponent($componentPath),
            json_encode($props)
        );

        return implode("\n", $javascript);
    }
}