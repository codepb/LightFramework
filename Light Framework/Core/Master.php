<?php
namespace LightFramework\Core;

require_once('/Core/Routing/RouteCollection.php');
require_once('/Core/Routing/Route.php');

class Master
{
    public $config;
    
    public function Start()
    {
        //Anything to register at startup do here
        Routing\RouteCollection::Add(new Routing\Route("{Controller}/{Action}/{Id}"));
        Routing\RouteCollection::ProcessRoutes();
    }
}
?>
