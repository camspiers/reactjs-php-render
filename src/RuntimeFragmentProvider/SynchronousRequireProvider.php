<?php

namespace ReactJS\RuntimeFragmentProvider;

/**
 * @package ReactJS
 */
class SynchronousRequireProvider implements ProviderInterface
{
    /**
     * Returns fragments for getting a component in JavaScript
     *
     * E.g. require('component')
     *
     * @param $componentName
     * @return mixed
     */
    public function getComponent($componentName)
    {
        return sprintf(
            'require(%s)',
            json_encode($componentName)
        );
    }

    /**
     * Returns fragment for getting the react object
     * @return string
     */
    public function getReact()
    {
        return "require('react')";
    }
}