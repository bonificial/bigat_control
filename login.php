<?php

include 'config/constants.php';
!isset($_SESSION) ? session_start() : null;
$user = $_SESSION;
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="830613963245-6uff749i3hb4a3dnp0oeuolkmdahtq7f.apps.googleusercontent.com">

    <title>Login Page</title>
    <?php
    include './common/header.php';
    ?>
    <!--Custom styles-->

    <link rel="stylesheet" type="text/css" href="<?php echo asset(STYLES).'/login.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset(STYLES).'/loader.css'; ?>">
</head>
<body>
<?php include './common/loader.php'?>
<div class="container">
    <div class="d-flex justify-content-between flex-row  h-100">
        <div class="card align-self-start" style="height: auto">
            <div class="card-header">
                <h3 class="text-center">Welcome to BIGAT</h3>
                <h5 class="text-center text-white">Admin Console</h5>

            </div>

            <div class="card-body">

                <div class="d-flex flex-column align-items-center justify-content-center" style="color: #e5d118" >
                    <h5>Credits to</h5>
                    <img class=" "  style="margin:8px 0; height: 80px" src="assets/images/inverse_logo.png"/>
                    <h5>KPIF</h5>
                </div>
            </div>

        </div>
        <div class="card align-self-end" style="height: auto">
            <div class="card-header">
                <h3 class="text-center">Sign In</h3>

            </div>

            <div class="card-body">
                <form>
                    <!--<div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input id="username" type="text" class="form-control" placeholder="Email">

                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input id="password" type="password" class="form-control" placeholder="Password">
                    </div>

                    <div class="input-group form-group">
                        <button id="submit_login" type="button" class="btn float-left login_btn btn-block login-submit">LOGIN</button>

                    </div>-->
                    <div class="d-flex justify-content-center " style="padding:15px">
                        <img class=" " style="height: 70px" src="assets/images/logo_small.png"/>

                    </div>
                    <div class="input-group form-group">
                      <!--  <button   id="g_sign_inBtn" class="btn btn-block btn-lg btn-social btn-google-plus"> Sign in with Gmail </button>-->
                        <div id="g_signIn"  style="margin: 0 auto"  class="g-signin2"  ></div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
<div style="position: absolute;bottom: 0;width: 100%">
<?php
include './common/footer.php';
?>
</div>
<script>
    function renderButton() {
        gapi.signin2.render('g_signIn', {
            'scope': 'profile email',
            'width': 300,
            'height': 50,
            'longtitle': true,
            'theme': 'dark',
            'onsuccess': onSignIn,

        });
    }
</script>
<script src="https://apis.google.com/js/platform.js?onload=renderButton"></script>
<script src="<?php asset(SCRIPTS);?>/login.js"></script>

</body>
</html>