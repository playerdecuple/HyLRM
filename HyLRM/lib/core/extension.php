
<?php

    function load_extensions() {
        $extensions = scandir("./HyLRM_Extensions");

        foreach ($extensions as $dir) {
            if ($dir == "." || $dir == "..")
                continue;

            if (!is_dir("./HyLRM_Extensions/$dir"))
                continue;

            require "./HyLRM_Extensions/" . $dir . "/main.php";
        }
    }

?>