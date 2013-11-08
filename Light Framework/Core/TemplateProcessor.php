<?php
namespace LightFramework\Core;

require_once('/Core/HtmlHelper.php');

class TemplateProcessor
{
    private $view;
    private $model;
    public $ViewBag;
    public $body;
    public $sections;
    private $debug;
    private $content;
    public $HtmlHelper;

    public function __construct($view, $model)
    {
        $this->view = $view;
        $this->model = $model;
        $this->HtmlHelper = new HtmlHelper;
    }

    public function contents($store_content = false)
    {
        $model = $this->model;
        ob_start();
        include($this->view);
        $content = ob_get_contents();
        ob_end_clean();
        if($store_content)
        {
            $this->content = $content;
        }
        return $content;
    }

    public function output()
    {
        echo $this->contents();
    }

    private function RenderBody()
    {
        if(!empty($this->body))
        {
            echo $this->body;
        }
    }

    private function RenderSection($name)
    {
        if(!empty($this->sections[$name]))
        {
            echo $this->sections[$name];
        }
    }

    public function ProcessSections()
    {
        if(empty($this->content))
        {
            $this->contents(true);
        }
        $content = $this->content;
        $count = strlen($content);
        $in_section = FALSE;
        $current_section_name = "";
        for($i = 0; $i < $count; $i++)
        {
            if($content[$i] == '<')
            {
                if(strcasecmp(substr($content, $i, 11),"<!--SECTION") == 0)
                {
                    $in_section = TRUE;
                    $current_section_name = str_replace("-->","",$this->GetNextWord($content, $i+12));
                    $this->sections[$current_section_name] = "";
                    $index_of_comment_end = strpos(substr($content, $i), "-->");
                    $i += $index_of_comment_end + 2;
                    continue;
                }
                elseif($in_section && strcasecmp(substr($content, $i, 17),"<!--ENDSECTION-->") == 0)
                {
                    $in_section = FALSE;
                    $i += 16;
                    continue;
                }
            }
            if($in_section)
            {
                $this->sections[$current_section_name] .= $content[$i];
            }
            else
            {
                $this->body .= $content[$i];
            }
        }
    }

    private function GetNextWord($string, $index)
    {
        $substring = substr($string, $index);
        $pos_of_next_space = strpos($substring, " ");
        $pos_of_end_comment = strpos($substring, "-->");
        if(!$pos_of_next_space || $pos_of_end_comment < $pos_of_next_space)
        {
            return substr($substring, 0, $pos_of_end_comment);
        }
        else
        {
            return substr($substring, 0, $pos_of_next_space);
        }
        
    }
}
?>
