<?php
    include 'migrate.php';

    Migration::alterField( 'users', 'cookievalue', 'cookievalue', 'TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL' );
?>
