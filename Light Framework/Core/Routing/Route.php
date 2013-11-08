<?php
namespace LightFramework\Core\Routing;

require_once('/Core/Routing/RouteParser.php');

class Route
{
    private $url;
    private $default;
    private $processed_route;

    public function __construct($url, $default = array('Controller' => 'Home', 'Action' => 'Index'))
    {
        $this->url = $url;
        $this->default = $default;
    }

    public function ProcessRoute()
    {
        $path["Action"] = empty($this->processed_route->action) ? $this->default["Action"] : $this->processed_route->action;
        $path["Controller"] = empty($this->processed_route->controller) ? $this->default["Controller"] : $this->processed_route->controller;
        $class = '\\LightFramework\\Controllers\\' . $path["Controller"] . 'Controller';

        $file = '/Controllers/' . $path["Controller"] . 'Controller.php';

        require_once($file);

        $controller = new $class();
        if(count($_POST) > 0)
        {
            //TODO: build the model here;
            $action = "Post" . $path["Action"];
            $model_name = '\\LightFramework\\Models\\' . $path["Action"];
            require_once("/Models/" . $path["Action"] . ".php");
            $model = new $model_name();
            foreach($_POST as $key => $value)
            {
                if(property_exists($model_name, $key))
                {
                    $model->$key = $value;
                }
            }
            $controller->$action($model);
        }
        else
        {
            $action = "Get" . $path["Action"];
            unset($_GET['path']);
            $controller->$action($_GET);
        }
        
    }

    public function MatchesPath($path)
    {
        $this->processed_route = RouteParser::ParseRoute($this->url, $path);
        return !empty($this->processed_route);
    }

    public function CreateUrl($tokens)
    {
        return RouteParser::CreateUrl($this->url, $tokens);
    }
}
?>
