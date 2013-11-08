<?php
namespace LightFramework\Core;

abstract class BaseController
{    
    protected function View($model = null)
    {
        $method = $this->GetCallingMethod();
        $class = explode('\\', $this->GetCallingClass());
        $class = str_replace('Controller', '', $class[count($class) - 1]);
        $tpl_processor = new TemplateProcessor("/Views/$class/" . str_replace(array("Get","Post"), "", $method) . ".lftpl", $model);
        $tpl_processor->ProcessSections();
        $this->PrepareViewStart($tpl_processor->body, $tpl_processor->sections, $tpl_processor->ViewBag);
    }

    private function GetCallingMethod()
    {
        $callers=debug_backtrace();
        return $callers[2]['function'];
    }

    private function GetCallingClass()
    {
        $callers=debug_backtrace();
        return $callers[2]['class'];
    }

    private function PrepareViewStart($body, $sections, $viewbag)
    {
        $view_start_app = "/Views/_ViewStart.lftpl";
        $output = FALSE;
        if( stream_resolve_include_path($view_start_app) !== false)
        {
            $model = array();
            $tpl_processor = new TemplateProcessor($view_start_app, $model);
            $tpl_processor->body = $body;
            $tpl_processor->sections = $sections;
            $view_start_contents = $tpl_processor->contents();
            $layout = $tpl_processor->ViewBag["Layout"];
            if(!empty($layout))
            {
                $tpl_processor = new TemplateProcessor($layout, null);
                $tpl_processor->ViewBag = $viewbag;
                $tpl_processor->body = $body;
                $tpl_processor->sections = $sections;
                $tpl_processor->output();
                $output = TRUE;
            }
        }
        if(!$output)
        {
            echo $body;
        }
    }
}
?>