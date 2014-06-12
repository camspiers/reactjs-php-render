<?php

namespace ReactJS\Renderer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

/**
 * @package ReactJS
 */
class HTTPServerRenderer implements RendererInterface
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
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param string $host
     * @param int $port
     * @param string $path
     * @param bool $ssl
     */
    public function __construct(
        $host,
        $port,
        $path = '',
        $ssl = false
        LoggerInterface $logger = null
    )
    {
        $this->host = (string) $host;
        $this->port = (int) $port;
        $this->path = (string) $path;
        $this->ssl = (bool) $ssl;
        $this->logger = $logger;
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
        $markup = '';

        $client = new Client();

        try {
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

            $markup = (string) $response->getBody();
        } catch (RequestException $e) {
            if ($this->logger instanceof LoggerInterface) {
                $this->logger->error($e->getMessage());
            }
        }

        return $markup;
    }
}