<?php

namespace ReactJS\RuntimeFragmentProvider;

interface ProviderInterface
{
    /**
     * Returns fragment for getting a component in JavaScript
     * 
     * E.g. require('component')
     * 
     * @param $componentName
     * @return mixed
     */
    public function getComponent($componentName);

    /**
     * Returns fragment for getting the react object
     * @return string
     */
    public function getReact();
}