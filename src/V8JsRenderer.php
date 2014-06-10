<?php

namespace ReactJS;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Traversable;
use V8Js;
use V8JsException;
use RuntimeException;

/**
 * @package ReactJS
 */
class V8JsRenderer implements RenderInterface
{
    /**
     * @var \V8Js
     */
    protected $v8;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var array|\Traversable
     */
    protected $sourceFiles;

    /**
     * @param \V8Js $v8
     * @param array|\Traversable $sourceFiles
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        V8Js $v8,
        $sourceFiles,
        LoggerInterface $logger = null
    )
    {
        $this->v8 = $v8;
        $this->setSourceFiles($sourceFiles);
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
     * @param $componentPath
     * @param array|null $props
     * @return string
     */
    public function renderComponentToString($componentPath, $props = null)
    {
        return $this->render(__FUNCTION__, $componentPath, $props);
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderComponentToStaticMarkup($componentPath, $props = null)
    {
        return $this->render(__FUNCTION__, $componentPath, $props);
    }

    /**
     * @param $reactFunction
     * @param $componentPath
     * @param null $props
     * @return string
     * @throws \RuntimeException
     */
    private function render($reactFunction, $componentPath, $props = null)
    {
        $react = [];

        $react[] = "var console = { warn: print, error: print };";
        $react[] = "var global = {};";

        foreach ($this->sourceFiles as $sourceFile) {
            $react[] = file_get_contents($sourceFile) . ";";
        }

        $react[] = "var React = require('react');";
        $react[] = sprintf(
            "var Component = require(%s);",
            json_encode($componentPath)
        );

        $react[] = sprintf(
            "var markup = React.%s(Component(%s));",
            $reactFunction,
            json_encode($props)
        );

        $markup = '';

        try {
            ob_start();
            $markup = $this->v8->executeString(implode("\n", $react));
            $errors = ob_end_clean();

            if ($errors) {
                $this->log(
                    "Errors in v8 javascript execution",
                    [
                        "errors" => $errors
                    ]
                );
            }

            if (!is_string($markup)) {
                throw new RuntimeException("Value returned from v8 executeString isn't a string");
            }
        } catch (V8JsException $e) {
            $this->log($e->getMessage());
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