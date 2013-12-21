<?php
    include 'migrate.php';

    migrate(
        array(
            'ALTER TABLE
                roundcreatures
            DROP INDEX
                uc_roundcreatures',
            'ALTER TABLE
                roundcreatures
            ADD CONSTRAINT pk_roundcreatures PRIMARY KEY (gameid,roundid,creatureid)'
        )
    );
?>
