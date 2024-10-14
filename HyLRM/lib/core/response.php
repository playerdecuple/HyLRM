<?php


    class Response {

        public function status($code) {
            http_response_code($code);
            return $this;
        }


        public function headers($header_array) {
            foreach ($header_array as $h_k => $h_v) {
                header($h_k. ": ". $h_v);
            }

            return $this;
        }


        public function json($data) {
            header("Content-Type: application/json");

            if (is_array($data)) {
                $this -> response(json_encode($data));
            } else {
                $this -> response($data);
            }
        }


        public function raw($raw) {
            $this -> response($raw);
        }


        public function render($path_array, $data) {
            if (!is_array($path_array)) {
                $path_array = [ $path_array ];
            }

            extract($data);

            foreach ($path_array as $path) {
                require $path;
            }

            die;
        }


        public function html($path) {
            header("Content-Type: text/html");
            $this -> response(file_get_contents($path));
        }


        public function file($path, $content_type = "text/plain") {
            if ($content_type == "video/mp4") {
                $this -> stream($path);
                return;
            }

            header("Content-Type: $content_type");

            ob_clean();
            ob_start();
            readfile($path);
            ob_flush();
        }


        public function stream($path) {
            $fp = @fopen($path, 'rb');
            $size = filesize($path);
            $length = $size;

            $start = 0;
            $end = $size - 1;

            $this -> headers([
                "Content-Type" => "video/mp4",
                "Accept-Ranges" => "bytes"
            ]);

            if (isset($_SERVER['HTTP_RANGE'])) {
                $c_start = $start;
                $c_end = $end;

                list(, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);

                if (strpos($range, ",") !== false) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    exit;
                }
            

                if ($range == "-") {
                    $c_start = $size - substr($range, 1);
                } else {
                    $range = explode("-", $range);
                    $c_start = $range[0];
                    $c_end = (isset($range[1]) && is_numeric($range[1]))
                        ? $range[1]
                        : $size;
                }

                $c_end = ($c_end > $end)
                    ? $end
                    : $c_end;

                if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    exit;
                }

                $start = $c_start;
                $end = $c_end;
                $length = $end - $start + 1;

                fseek($fp, $start);
                header("HTTP/1.1 206 Partial Content");
            }

            header("Content-Range: bytes $start-$end/$size");
            header("Content-Length: " . $length);
            header("Content-Disposition: inline;");
            header("Content-Transfer-Encoding: binary\n");
            header('Connection: Close');
            

            $buffer = 1024 * 8;
            while (!feof($fp) && ($p = ftell($fp)) <= $end) {
                if ($p + $buffer > $end) {
                    $buffer = $end - $p + 1;
                }

                set_time_limit(0);

                ob_clean();
                echo fread($fp, $buffer);
                ob_flush();
                flush();
            }

            fclose($fp);
            exit();
        }


        private function response($str) {
            echo $str;
            die;
        }

    }


?>