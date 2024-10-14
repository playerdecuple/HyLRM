<?php

    require "core/session.php";
    require "core/router.php";
    require "core/modularized.php";
    require "core/response.php";
    require "core/extension.php";



    class HyLRM {


        public static $router;
        


        public static function log() {
            echo var_dump(...func_get_args());
        }


        private static function load_modules() {
            load_modules();
        }


        private static function load_extensions() {
            load_extensions();
        }


        private static function validate_request() {
            $blacklists = [
                "/HyLRM/",
                "/HyLRM_Extensions/",
                "/HyLRM_Modules/",
            ];

            foreach ($blacklists as $blacklist) {
                if (str_starts_with(HyLRM_Data["U"], $blacklist)) {
                    http_response_code(403);
                    die;
                }
            }
        }

        
        public static function ready() {
            session_ready();

            HyLRM::validate_request();
            self::$router = new Router("");

            HyLRM::load_extensions();
            HyLRM::load_modules();
        }


        public static function response() {
            return new Response();
        }


        public static function router($pathname) {
            return new Router($pathname);
        }


    }

?>