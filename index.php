<?php
    include 'models/database.php';
    $resource = $_GET[ 'resource' ];
    $method = $_GET[ 'method' ];
    if ( !isset( $resource ) && !isset( $method ) ) {
        $resource = 'Dashboard';
        $method = 'listing';
    }
    $controller = $resource . 'Controller';
    include 'controllers/' . strtolower( $resource ) . '.php';
    $controller::$method();
?>
