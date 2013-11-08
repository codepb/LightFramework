<?php
namespace LightFramework\Core\Routing;

require_once('/Core/Routing/Route.php');

class RouteCollection
{
    private static $routes = array();

    public static function ProcessRoutes()
    {
        $path = $_GET['path'];
        foreach(static::$routes as $route)
        {
            if($route->MatchesPath($path))
            {
                $route->ProcessRoute();
                return;
            }
        }
    }

    public static function Add($route)
    {
        if(!is_object($route) || get_class($route) != "LightFramework\Core\Routing\Route")
        {
            throw new \InvalidArgumentException('You can only add an object of type Route');
        }
        static::$routes[] = $route;
    }

    public static function CreateUrl($tokens)
    {
        //TODO check which matches and use that
        return static::$routes[0]->CreateUrl($tokens);
    }
}
?>