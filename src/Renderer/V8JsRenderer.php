<?php

namespace ReactJS\Renderer;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use ReactJS\RuntimeFragmentProvider\SynchronousRequireProvider;
use Traversable;
use V8Js;
use V8JsException;
use RuntimeException;
use ReactJS\RuntimeFragmentProvider\ProviderInterface;

/**
 * @package ReactJS
 */
class V8JsRenderer implements RendererInterface
{
    /**
     * @var \V8Js
     */
    protected $v8;

    /**
     * @var \ReactJS\RuntimeFragmentProvider\ProviderInterface
     */
    protected $fragmentProvider;

    /**
     * @var array|\Traversable
     */
    protected $sourceFiles;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

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
        $this->fragmentProvider = $fragmentProvider ?: new SynchronousRequireProvider();
        $this->setSourceFiles($sourceFiles);
        $this->v8 = $v8 ?: new V8Js;
        $this->logger = $logger;
    }

    /**
     * @param array|\Traversable $sourceFiles
     * @throws \InvalidArgumentException
     */
    public function setSourceFiles($sourceFiles)
    {
        if (!(is_array($sourceFiles) || $sourceFiles instanceof Traversable)) {
            throw new InvalidArgumentException(
                "\$sourceFiles passed to setSourceFiles must be iterable"
            );
        }

        foreach ($sourceFiles as $sourceFile) {
            if (!is_readable($sourceFile)) {
                throw new InvalidArgumentException(
                    sprintf(
                        "File '%s' doesn't exist or is not readable",
                        $sourceFile
                    )
                );
            }
        }

        $this->sourceFiles = $sourceFiles;
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
     * @throws \RuntimeException
     * @throws \V8JsException
     */
    private function render($reactFunction, $componentPath, $props = null)
    {
        $react = [];

        $react[] = "var console = { warn: print, error: print };";

        // Clear any module loaders
        if (method_exists($this->v8, 'setModuleLoader') && $this->fragmentProvider instanceof SynchronousRequireProvider) {
            $react[] = "function require() {}";
            $react[] = "var require = null;";
        }

        foreach ($this->sourceFiles as $sourceFile) {
            $react[] = file_get_contents($sourceFile) . ";";
        }

        $react[] = sprintf(
            "%s.%s(%s(%s));",
            $this->fragmentProvider->getReact(),
            $reactFunction,
            $this->fragmentProvider->getComponent($componentPath),
            json_encode($props)
        );

        $markup = '';

        try {
            ob_start();
            $markup = $this->v8->executeString(implode("\n", $react));
            $errors = ob_get_clean();

            if ($errors) {
                $this->log(
                    "Errors in v8 javascript execution",
                    ["errors" => $errors]
                );
            }

            if (!is_string($markup)) {
                throw new RuntimeException("Value returned from v8 executeString isn't a string");
            }
        } catch (V8JsException $e) {
            $this->log($e->getMessage());

            if ($reactFunction === 'renderComponentToStaticMarkup') {
                throw $e;
            }
        }

        return $markup;
    }

    /**
     * @param $message
     * @param array $context
     */
    protected function log($message, array $context = array())
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->error($message, $context);
        }
    }
}