<?php

    function connect() {

        $server = "localhost";
        $username = "root";
        $password = "";
        $c = mysqli_connect($server, $username, $password,'moodys');
        mysqli_select_db($c,'moodys');
        return $c;
    }
?>			