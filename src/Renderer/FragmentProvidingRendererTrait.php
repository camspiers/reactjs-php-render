<?php

namespace ReactJS\Renderer;

use ReactJS\RuntimeFragmentProvider\ProviderInterface;
use ReactJS\RuntimeFragmentProvider\SynchronousRequireProvider;

trait FragmentProvidingRendererTrait
{
    /**
     * @var \ReactJS\RuntimeFragmentProvider\ProviderInterface
     */
    protected $fragmentProvider;

    /**
     * @param \ReactJS\RuntimeFragmentProvider\ProviderInterface $fragmentProvider
     */
    public function setFragmentProvider(ProviderInterface $fragmentProvider)
    {
        $this->fragmentProvider = $fragmentProvider;
    }

    /**
     * @return \ReactJS\RuntimeFragmentProvider\ProviderInterface
     */
    public function getFragmentProvider()
    {
        return $this->fragmentProvider ?: new SynchronousRequireProvider();
    }
}