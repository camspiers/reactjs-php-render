<?php

namespace ReactJS\Renderer;

use Symfony\Component\Process\Process;

/**
 * @package ReactJS\Renderer
 */
trait StdInProcessRendererTrait
{
    use LoggingRendererTrait;

    /**
     * @var
     */
    protected $bin;

    /**
     * @param mixed $bin
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @return mixed
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess()
    {
        return new Process($this->bin);
    }

    /**
     * @param string $input
     * @return string
     */
    protected function getOutput($input)
    {
        return $this->run($input)->getOutput();
    }

    /**
     * @param string $input
     * @return \Symfony\Component\Process\Process
     */
    protected function run($input)
    {
        $process = $this->getProcess();
        $process->setInput($input);
        $process->run();
        
        if ($error = $process->getErrorOutput()) {
            $this->log('Error during rendering', [
                'stderr' => $error
            ]);
        }
        return $process;
    }
}