<?php
    class ModelNotFoundException extends Exception {
        public function __construct() {
            parent::__construct( 'NotFound' );
        }
    }

    class ModelValidationException extends Exception {
        public $error;
        public function __construct( $error = "" ) {
            parent::__construct( "Model validation error: " . $error );
            $this->error = $error;
        }
    }
?>
