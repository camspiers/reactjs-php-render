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
    }

    /**
     * @param $componentPath
     * @param array|void $props
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
     */
    private function render($reactFunction, $componentPath, $props = null)
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
                    "reactFunction" => $reactFunction,
                    "componentPath" => $componentPath,
                    "props" => json_encode($props)
                ]
            ]
        );

        return (string) $response->getBody();
    }
}