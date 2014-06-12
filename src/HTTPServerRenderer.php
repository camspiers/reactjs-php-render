<?php

namespace ReactJS;

use GuzzleHttp\Client;

/**
 * @package ReactJS
 */
class HTTPServerRenderer implements RenderInterface
{
    /**
     * @var bool
     */
    protected $ssl = false;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $host
     * @param int $port
     * @param string $path
     * @param bool $ssl
     */
    public function __construct($host, $port, $path = '', $ssl = false)
    {
        $this->host = (string) $host;
        $this->port = (int) $port;
        $this->path = (string) $path;
        $this->ssl = (bool) $ssl;
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
        return $this->render('mountable', $componentPath, $props);
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function renderStaticComponent($componentPath, $props = null)
    {
        return $this->render('static', $componentPath, $props);
    }

    /**
     * @param $reactRenderType
     * @param $componentPath
     * @param null $props
     * @return string
     */
    private function render($reactRenderType, $componentPath, $props = null)
    {
        $client = new Client();
    
        $response = $client->post(
            sprintf(
                "http%s://%s:%s/%s",
                $this->ssl ? 's' : '',
                $this->host,
                $this->port,
                ltrim($this->path, '/')
            ),
            [
                "body" => [
                    "renderType" => $reactRenderType,
                    "componentPath" => $componentPath,
                    "props" => json_encode($props)
                ]
            ]
        );

        return (string) $response->getBody();
    }
}