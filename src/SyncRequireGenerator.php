<?php

namespace ReactJS;


class SyncRequireGenerator implements GenerateRenderRuntimeInterface
{
    /**
     * @param $componentPath
     * @param array|void $props
     * @param string $renderedMountableComponentMarkup
     * @return string
     */
    public function mountedComponentMarkup($componentPath, $props = null, $renderedMountableComponentMarkup = '')
    {
        $container_id = uniqid();

        $container_markup = sprintf(
            "<div id=\"%s\">%s</div>",
            $container_id,
            $renderedMountableComponentMarkup
        );

        $script_markup = sprintf(
            "<script>require('react').renderComponent(require(%s)(%s),document.getElementById(%s))</script>",
            json_encode($componentPath),
            json_encode($props),
            json_encode($container_id)
        );

        return $container_markup . $script_markup;
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function mountableComponentJS($componentPath, $props = null)
    {
        $markup = sprintf(
            "require('react').renderComponentToString(require(%s)(%s));",
            json_encode($componentPath),
            json_encode($props)
        );

        return $markup;
    }

    /**
     * @param $componentPath
     * @param array|void $props
     * @return string
     */
    public function staticComponentJS($componentPath, $props = null)
    {
        $markup = sprintf(
            "require('react').renderComponentToStaticMarkup(require(%s)(%s));",
            json_encode($componentPath),
            json_encode($props)
        );

        return $markup;
    }
} 