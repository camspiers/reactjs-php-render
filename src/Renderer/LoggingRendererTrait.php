<?php

namespace ReactJS\Renderer;

use Psr\Log\LoggerInterface;

trait LoggingRendererTrait
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $logType = 'error';

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param string $logType
     */
    public function setLogType($logType)
    {
        $this->logType = $logType;
    }

    /**
     * @return string
     */
    public function getLogType()
    {
        return $this->logType;
    }

    /**
     * @param $message
     * @param array $context
     */
    protected function log($message, array $context = [])
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->{$this->logType}($message, $context);
        }
    }
}