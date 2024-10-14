<?php


    class Router {


        public $router_uri;

        
        public function __construct($router_uri = "") {
            if (str_ends_with($router_uri, "/")) {
                $router_uri = preg_replace("/\/+$/", "", $router_uri);
            }

            if (trim($router_uri) != "" && !str_starts_with($router_uri, "/")) {
                $router_uri = "/" . $router_uri;
            }

            $this -> router_uri = trim($router_uri);
        }


        public function before($runnable, $callback) {
            before($runnable, $callback);
        }


        public function get($uri, $fn) {
            $uri = ($this -> router_uri).$uri;
            get($uri, $fn);
        }


        public function post($uri, $fn) {
            $uri = ($this -> router_uri).$uri;
            post($uri, $fn);
        }


        public function put($uri, $fn) {
            $uri = ($this -> router_uri).$uri;
            put($uri, $fn);
        }


        public function patch($uri, $fn) {
            $uri = ($this -> router_uri).$uri;
            patch($uri, $fn);
        }


        public function delete($uri, $fn) {
            $uri = ($this -> router_uri).$uri;
            delete($uri, $fn);
        }


        public function router($uri) {
            return new Router(($this -> router_uri).$uri);
        }


    }



    function get() {
        check_route("GET", ...func_get_args()); 
    }


    function post() {
        check_route("POST", ...func_get_args());
    }


    function put() {
        check_route("PUT", ...func_get_args());
    }


    function patch() {
        check_route("PATCH", ...func_get_args());
    }


    function delete() {
        check_route("DELETE", ...func_get_args());
    }


    function check_route($method, $uri, $callback, $reg = false) {
        $escapes = [
            "\\" => "\\\\",
            "(" => "\(",
            ")" => "\)",
            "[" => "\[",
            "]" => "\]",
            "^" => "\^",
            "+" => "\+",
            "." => "\.",
            "*" => "(.*)",
            "$" => "([^/]+)",
            "/" => "\/",
        ];

        $params = null;

        if (!$reg) {
            $uri = str_replace(
                array_keys($escapes), 
                array_values($escapes), 
                $method.$uri
            );
    
            $request_uri = HyLRM_Data["M"].HyLRM_Data["U"];
    
            if (!preg_match_all("/^$uri$/", $request_uri, $params))
                return;
        } else {
            if ($method != HyLRM_Data["M"])
                return;

            if (!preg_match_all("/$reg/", HyLRM_Data["U"], $params))
                return;
        }

        foreach (debug_backtrace() as $trace)
            if ($trace['function'] == 'before') {
                $next = $trace['args'][0]();

                if (!$next)
                    return;

                break;
            }

        array_shift($params);
        $params = array_map(function ($v) {
            return $v[0];
        }, $params);

        $callback(...$params);
        die;
    }


    function before($runnable, $callback) {
        $callback();
    }


?>
