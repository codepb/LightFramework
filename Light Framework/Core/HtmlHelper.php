<?php
namespace LightFramework\Core;

class HtmlHelper
{
    public function SiteRoot()
    {
        return "/Light Framework";
    }

    public function ActionLink($text, $action, $controller, $options = array())
    {
        return $this->StringFormat('<a href="{0}" class="{1}">{2}</a>', $this->ProcessLink($action, $controller), (isset($options['class']) ? $options['class'] : ""), $text);
    }

    public function ProcessLink($action, $controller)
    {
        $route = $this->SiteRoot() . '/' . Routing\RouteCollection::CreateUrl(array('action' => $action, 'controller' => $controller));
        return $route;
    }

    private function StringFormat()
    {
        $args = func_get_args();
        if (count($args) == 0) {
            return;
        }
        if (count($args) == 1) {
            return $args[0];
        }
        $str = array_shift($args);
        $str = preg_replace_callback('/\\{(0|[1-9]\\d*)\\}/', create_function('$match', '$args = '.var_export($args, true).'; return isset($args[$match[1]]) ? $args[$match[1]] : $match[0];'), $str);
        return $str;
    }
}
?>
