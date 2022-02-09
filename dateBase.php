<?php
    include('env.php');
        $host = getenv("DB_HOSTNAME");
        $dbname = getenv('DB_NAME');
            $dsn = "mysql:dbname=${dbname};host=${host};charset=utf8";

            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');
            $mysqli=new mysqli($host,$username,$password,$dbname);
        if($mysqli -> connect_error){
            echo $mysqli->connect_error;
            exit();
        }else{
            $mysqli -> set_charset('utf8');
        }
