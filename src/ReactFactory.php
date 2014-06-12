<?php

namespace ReactJS;

use ReactJS\RuntimeFragmentProvider\ProviderInterface;
use ReactJS\Renderer\V8JsRenderer;
use ReactJS\Renderer\HTTPServerRenderer;
use ReactJS\RuntimeFragmentProvider\SynchronousRequireProvider;
use V8Js;
use Psr\Log\LoggerInterface;

/**
 * @package ReactJS
 */
class ReactFactory
{
    /**
     * @param $sourceFiles
     * @param ProviderInterface $fragmentProvider
     * @param V8Js $v8
     * @param LoggerInterface $logger
     * @return React
     */
    public static function createV8Renderer(
        $sourceFiles,
        ProviderInterface $fragmentProvider = null,
        V8Js $v8 = null,
        LoggerInterface $logger = null
    )
    {
        $fragmentProvider ?: new SynchronousRequireProvider();

        return new React(
            new V8JsRenderer(
                $sourceFiles,
                $fragmentProvider,
                $v8,
                $logger
            ),
            $fragmentProvider
        );
    }

    /**
     * @param $host
     * @param $port
     * @param string $path
     * @param bool $ssl
     * @param ProviderInterface $fragmentProvider
     * @return React
     */
    public static function createHTTPServerRenderer(
        $host,
        $port,
        $path = '',
        $ssl = false,
        ProviderInterface $fragmentProvider = null
    )
    {
        $fragmentProvider ?: new SynchronousRequireProvider();

        return new React(
            new HTTPServerRenderer($host, $port, $path, $ssl),
            $fragmentProvider
        );
    }
} 