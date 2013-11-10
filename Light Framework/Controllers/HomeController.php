<?php
namespace LightFramework\Controllers;

require_once('/Models/TestPage.php');
require_once('/Models/TwitterVote.php');
require_once('/Core/TemplateProcessor.php');
require_once('/Core/BaseController.php');

use LightFramework\Core;
use LightFramework\Models;

class HomeController extends Core\BaseController
{
    public function GetIndex($values)
    {
        $this->View();
    }

    public function GetRxDemo($values)
    {
        $this->View();
    }

    public function GetTwitterDemo($values)
    {
        $this->View();
    }

    public function GetTwitterLarge($values)
    {
        $this->View();
    }

    public function GetTwitterVote($values)
    {
        $model = new Models\TwitterVote;
        if(!empty($values["items"]))
        {
            $model->items = explode(',', $values["items"]);   
        }
        $this->View($model);
    }
    
    public function GetTestPage($values)
    {
        $model = new Models\TestPage;
        $model->text = "Test";
        $model->title = "Page Title";
        $this->View($model);
    }

    public function PostTestPage($model)
    {
        $this->View($model);
    }
}
?>