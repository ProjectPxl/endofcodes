<?php
    class UserController {
        public static function create( $username = '', $password = '', $email = '' ) {
            if ( empty( $username ) ) {
                go( 'user', 'create', array( 'empty_user' => true ) );
            }
            if ( empty( $password ) ) {
                go( 'user', 'create', array( 'empty_pass' => true ) );
            }
            if ( empty( $email ) ) {
                go( 'user', 'create', array( 'empty_mail' => true ) );
            }
            include 'models/users.php';
            include 'models/mail.php';
            $_SESSION[ 'create_post' ] = array(
                'username' => $username,
                'email' => $email
            );
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            $user->email = $email;
            try {
                $user->save();
                $id = $user->id;
            }
            catch( ModelValidationException $e ) {
                go( 'user', 'create', array( $e->error => true ) );
            }
            $_SESSION[ 'user' ] = array(
                'id' => $id,
                'username' => $username
            );
            go();
        }

        public static function view( $username, $notvalid ) {
            if ( $username === NULL ) {
                throw new HTTPNotFoundException();
            }
            include 'models/users.php';
            include 'models/extentions.php';
            include 'models/image.php';
            try { 
                $user = User::find_by_username( $username );
            }
            catch ( ModelNotFoundException $e ) {
                throw new HTTPNotFoundException();
            }
            $config = getConfig();
            $image = new Image( $username );
            $avatarname = $image->getCurrentImage();
            $target_path = $config[ 'paths' ][ 'avatar_path' ] . $avatarname;
            include 'views/user/view.php';
        }

        public static function update( $password_old, $password_new, $password_repeat ) {
            include 'models/users.php';
            if ( !isset( $_SESSION[ 'user' ] ) ) {
                throw new HTTPUnauthorizedException();
            }
            $user = new User( $_SESSION[ 'user' ][ 'id' ] );
            if ( $user->authenticatesWithPassword( $password_old ) ) {
                if ( $password_new != $password_repeat ) {
                    go( 'user', 'update', array( 'not_matched' => true ) );
                }
                $user->password = $password_new;
                $user->save();
                go();
            }
            go( 'user', 'update', array( 'old_pass' => true ) );
        }

        public static function delete() {
            include 'models/users.php';
            if ( !isset( $_SESSION[ 'user' ] ) ) {
                throw new HTTPUnauthorizedException();
            }
            $user = new User( $_SESSION[ 'user' ][ 'id' ] );
            $user->delete();
            unset( $_SESSION[ 'user' ] );
            go();
        }

        public static function createView( $empty_user, $empty_mail, $empty_pass, $user_used, $small_pass, $mail_used, $mail_notvalid ) {
            include 'views/user/create.php';
        }

        public static function updateView( $small_pass, $not_matched, $old_pass ) {
            include 'views/user/update.php';
        }
    }
?>
