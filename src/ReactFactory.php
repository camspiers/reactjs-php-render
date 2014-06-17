<?php

namespace ReactJS;

use ReactJS\Renderer\NullRenderer;
use ReactJS\RuntimeFragmentProvider\ProviderInterface;
use ReactJS\Renderer\V8JsRenderer;
use ReactJS\Renderer\HTTPServerRenderer;
use ReactJS\Renderer\NodeProcessRenderer;
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
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface $fragmentProvider
     * @param \V8Js $v8
     * @param \Psr\Log\LoggerInterface $logger
     * @return \ReactJS\React
     */
    public static function createUsingV8(
        $sourceFiles,
        ProviderInterface $fragmentProvider = null,
        V8Js $v8 = null,
        LoggerInterface $logger = null
    )
    {
        $fragmentProvider = $fragmentProvider ?: new SynchronousRequireProvider();

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
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface $fragmentProvider
     * @param \Psr\Log\LoggerInterface $logger
     * @return \ReactJS\React
     */
    public static function createUsingHTTPServer(
        $host,
        $port,
        $path = '',
        $ssl = false,
        ProviderInterface $fragmentProvider = null,
        LoggerInterface $logger = null
    )
    {
        $fragmentProvider ?: new SynchronousRequireProvider();

        return new React(
            new HTTPServerRenderer($host, $port, $path, $ssl, $logger),
            $fragmentProvider
        );
    }

    /**
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface $fragmentProvider
     * @return \ReactJS\React
     */
    public static function createUsingNull(ProviderInterface $fragmentProvider = null)
    {
        $fragmentProvider ?: new SynchronousRequireProvider();

        return new React(
            new NullRenderer(),
            $fragmentProvider
        );
    }

    /**
     * @param $bin
     * @param $sourceFiles
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface $fragmentProvider
     * @param \Psr\Log\LoggerInterface $logger
     * @return \ReactJS\React
     */
    public static function createUsingNode(
        $bin,
        $sourceFiles,
        ProviderInterface $fragmentProvider = null,
        LoggerInterface $logger = null
    )
    {
        $fragmentProvider = $fragmentProvider ?: new SynchronousRequireProvider();

        return new React(
            new NodeProcessRenderer(
                $bin,
                $sourceFiles,
                $fragmentProvider,
                $logger
            ),
            $fragmentProvider
        );
    }
} 