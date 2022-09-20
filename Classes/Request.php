<?php
namespace Classes;

/**
 * Class Request
 * This class is used to handle the request
 */
class Request {
    /**
     * Parse the URI to return array
     * @return string
     */
    public static function parseURI()
    {
        try {
            $uri = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
            unset($uri[0]);
            return trim(implode('/',$uri),'/');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Checks the method and the route, if it was matched, then it runs the callback function
     * @param string $method
     * @param string $path
     * @param callable $callback
     * @return bool
     */
    public static function checkService($method, $path, callable $callback)
    {
        preg_match_all('/\{(.*?)\}/', $path, $vars);    // extract the variables
        $var_count = count($vars[1]);
        $var_value = null;

        if ($var_count == 1) {                          // works with 1 parameter
            $uri_arr = explode('/', self::parseURI());
            $var_value = end($uri_arr);
            $path = str_replace('{'.$vars[1][0].'}',$var_value,$path);  // replace the value of the parameter in the $path
        } elseif ($var_count == 2) {
            $uri_arr = explode('/', self::parseURI());
            $var_value2 = array_pop($uri_arr);
            $var_value1 = array_pop($uri_arr);
            $path = str_replace('{'.$vars[1][0].'}',$var_value1,$path);
            $path = str_replace('{'.$vars[1][1].'}',$var_value2,$path);
        }
        
        if ($method==$_SERVER['REQUEST_METHOD'] and trim($path,'/')==self::parseURI())
        {   
            if ($var_count==2) {
                $callback($var_value1, $var_value2);  // run the callback function
            } elseif ($var_count==1) {
                $callback($var_value);
            } else {
                $callback();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check the text for empty value
     * @param string $text
     * @return bool
     */
    public static function checkParam($text)
    {
        return (isset($text) and !empty($text)) ? true : false;
    }

    /**
     * Check the params for empty value
     * @param array $params
     * @param array $required
     * @return bool
     */
    public static function checkParams($params, $required=null)
    {
        $result = true;
        if ($required) {
            foreach ($required as $key) {
                if (!array_key_exists($key, $params)) return false;
            }
        }
        foreach ($params as $key=>$param) {
            $result &= self::checkParam($param);
        }
        return $result;
    }

    /**
     * Get the params from the request
     * @return array
     */
    public static function getParams()
    {
        $params1 = $_REQUEST;
        $params2 = (array) json_decode(file_get_contents('php://input'),true);
        return array_merge($params1, $params2);
    }

    /**
     * Get the token from the request
     * @param string $replace
     * @return string
     */
    public static function getToken($replace='Bearer ')
    {
        $headers = apache_request_headers();
        return str_replace($replace,'',$headers['Authorization'] ?? '');
    }

    /**
     * Match the GET request
     * @param mixed $path
     * @param callable $callback
     * @return bool
     */
    public static function get($path, callable $callback)
    {
        return self::checkService('GET', $path, $callback);
    }

    /**
     * Match the POST request
     * @param mixed $path
     * @param callable $callback
     * @return bool
     */
    public static function post($path, $callback)
    {
        return self::checkService('POST', $path, $callback);
    }
    
    /**
     * Match the PUT request
     * @param mixed $path
     * @param callable $callback
     * @return bool
     */
    public static function put($path, $callback)
    {
        return self::checkService('PUT', $path, $callback);
    }

    /**
     * Match the DELETE request
     * @param mixed $path
     * @param callable $callback
     * @return bool
     */
    public static function delete($path, $callback)
    {
        return self::checkService('DELETE', $path, $callback);
    }
}