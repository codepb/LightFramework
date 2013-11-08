<?php
namespace LightFramework\Core\Routing;

require_once('/Core/Routing/IRouteParser.php');
require_once('/Core/Routing/Parsedroute.php');

class RouteParser implements IRouteParser
{
    private $tokens = array(
        'Controller' => '{Controller}',
        'Action' => '{Action}'
    );

    public static function CreateUrl($path, $tokens)
    {
        foreach($tokens as $key => $value)
        {
            $path = static::ReplaceToken($path, '{' . $key . '}', $value);
        }
        $parts = explode('/', $path);
        $found_tokens = array();
        for($i = 0; $i < count($parts); $i++)
        {
            $token = static::ParseToken($parts[$i]);
            if(count($token) > 0)
            {
                foreach($token as $cur_token => $value)
                {
                    $path = str_replace('{' . $cur_token . '}', "", $path);
                }
            }
        }
        return $path;
    }
    
    public static function ParseRoute($path, $url)
    {
        $parts = explode('/', $path);
        $url_parts = explode('/', $url);
        $parsed_route = new ParsedRoute;
        for($i = 0; $i < count($parts); $i++)
        {
            $token = static::ParseToken($parts[$i]);
            if(count($token) > 0)
            {
                if(!empty($url_parts[$i]))
                {
                    foreach($token as $key => $value)
                    {
                        preg_match( '/' . $value . '/', $url_parts[$i], $match);
                        if(!empty($match[1]))
                        {
                            switch ($key)
                            {
                                case 'Controller':
                                    $parsed_route->controller = $match[1];
                                    break;
                                case 'Action':
                                    $parsed_route->action = $match[1];
                                    break;
                                default:
                                    $parsed_route->parameters[$key] = $match[1];
                                    break;
                            }
                        }
                        else
                        {
                            if($url_parts[$i] != $parts[$i])
                            {
                                return;
                            }
                        }
                    }
                }
            }
            else
            {
                var_dump($parts);
                if(!empty($url_parts[$i]))
                {
                    if($url_parts[$i] == $parts[$i])
                    {
                        continue;
                    }
                }
                return;
            }
        }
        return $parsed_route;
    }

    private static function ParseToken($token)
    {
        $chars = str_split($token);
        $in_token = FALSE;
        $current_match = "";
        $return_string = "";
        foreach($chars as $char)
        {
            if($char == '{')
            {
                $in_token = TRUE;
                continue;
            }
            if(!$in_token)
            {
                $return_string .= $char;
            }
            if($char == '}')
            {
                $in_token = FALSE;
                $return_string .= "([^\/]+)";
            }
            if($in_token)
            {
                $current_match .= $char;
            }
        }
        return array($current_match => $return_string);
    }

    private static function ReplaceToken($path, $token, $value)
    {
        return str_ireplace($token, $value, $path);
    }
}
?>
