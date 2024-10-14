<?php

    function load_modules() {
        $module_directories = scandir("./HyLRM_Modules");

        foreach ($module_directories as $dir) {
            if ($dir == "." || $dir == "..")
                continue;

            if (!is_dir("./HyLRM_Modules/$dir"))
                continue;

            require "./HyLRM_Modules/" . $dir . "/main.php";
        }
    }

?>