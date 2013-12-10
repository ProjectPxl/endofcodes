<?php
    include 'views/header.php';
?>
<div id="steps">
    <div div="step1" class="steps">
        <p>Step 1</p>
        <p><a href="">Download the libraries</a></p>
    </div>
    <div id="step2" class="steps">
        <p>Step 2</p>
        <p><a href="">Read the basic rules</a></p>
    </div>
    <div id="step3" class="steps">
        <p>Step 3</p>
        <p>Code your bot</p>
    </div>
    <div id="step4" class="steps">
        <p>Step 4</p>
        <p>Register to try it out</p>
    </div>
</div>

<form id="register-form" action="index.php?resource=user&amp;method=create" method="post">
    <?php
        if ( isset( $empty_user ) ) { 
            ?><p class="error">Please type a username.</p><?php
        }
        if ( isset( $invalid_username ) ) { 
            ?><p class="error">Please insert a valid username</p><?php
        }
        else if ( isset( $empty_pass ) ) {
            ?><p class="error">Please type a password.</p><?php
        }
        else if ( isset( $empty_mail ) ) {
            ?><p class="error">Please type an email.</p><?php
        }
        else if ( isset( $empty_pass_repeat ) ) {
            ?><p class="error">Please repeat your password.</p><?php
        }
    ?>
    <label for="username">Username</label>
    <?php
        if ( isset( $user_used ) ) {
            ?><p class="error">Username already exists</p><?php
            $val = "";
        }
        else if ( isset( $_SESSION[ 'create_post' ][ 'username' ] ) ) {
            $val = $_SESSION[ 'create_post' ][ 'username' ];
            unset( $_SESSION[ 'create_post' ][ 'username' ] );
        }
        else {
            $val = "";
        }
    ?>
    <p><input type="text" id="username" name="username" value="<?php
        echo htmlspecialchars( $val );
    ?>"/></p>
    <label for="password">Password</label>
    <?php
        if ( isset( $small_pass ) ) {
            ?><p class="error">Password should be at least 7 characters long</p><?php
        }
        if ( isset( $not_matched ) ) {
            ?><p class="error">Passwords do not match</p><?php
        }
    ?>
    <p><input type="password" id="password" name="password" /></p>
    <label for="password_repeat">Repeat</label>
    <p><input type="password" id="password_repeat" name="password_repeat" /></p>
    <label for="email">Email</label>
    <?php
        if ( isset( $mail_used ) ) {
            ?><p class="error">Email is already used</p><?php
            $val = "";
        }
        else if ( isset( $mail_notvalid ) ) {
            ?><p class="error">This is not a valid email</p><?php
            $val = "";
        }
        else if ( isset( $_SESSION[ 'create_post' ][ 'email' ] ) ) {
            $val = $_SESSION[ 'create_post' ][ 'email' ];
            unset( $_SESSION[ 'create_post' ][ 'email' ] );
        }
        else {
            $val = "";
        }
    ?>
    <p><input type="text" id="email" name="email" value="<?php
        echo htmlspecialchars( $val );
    ?>"/></p>
    <label for="dob">Date of birth</label>
    <p>
        <select name="day" id="dob">
        <option>Select Day</option>
            <?php
                for ( $i = 1; $i <= 31; ++$i ) {
                    ?><option value="<?php
                        echo $i;
                    ?>"><?php
                        echo $i;
                    ?></option><?php
                }
            ?>
        </select> 
        <select name="month">
            <option>Select Month</option>
            <?php
                foreach ( $months as $month ) {
                    ?><option value="<?php
                        echo $month;
                    ?>"><?php
                        echo $month;
                    ?></option><?php
                }
            ?>
        </select>
        <select name="year">
            <option>Select Year</option>
            <?php
                for ( $i = 2007; $i >= 1910; --$i ) {
                    ?><option value="<?php
                        echo $i;
                    ?>"><?php
                        echo $i;
                    ?></option><?php
                }
            ?>
        </select> 
    </p> 
    <?php
        /*if ( isset( $empty_day ) ) {
            ?><p class="error">Please select a day</p><?php
        }
        if ( isset( $empty_month ) ) {
            ?><p class="error">Please select a month</p><?php
        }
        if ( isset( $empty_year ) ) {
            ?><p class="error">Please select a year</p><?php
        }
        if ( isset( $empty_country ) ) {
            ?><p class="error">Please select a country</p><?php
        }*/
    ?>
    <p>
        <select name="countryname">
            <option>Select Country</option>
            <?php
                foreach ( $countries as $country ) {
                    ?><option value="<?php
                        echo $country[ 'name' ];
                    ?>"><?php
                        echo $country[ 'name' ];
                    ?></option><?php
                }
            ?>
        </select> 
    </p>
    <?php
        /*if ( isset( $not_accepted ) ) {
            ?><p class="error">Please accept the terms of usage</p><?php
        }
        */
    ?>
    <!--<p><input type="checkbox" name="accept" />I agree on the <a href="">Terms of Usage</a></p>-->
    <p><input type="submit" value="Register" /></p>
</form>

<?php
    include 'views/footer.php';
?>
