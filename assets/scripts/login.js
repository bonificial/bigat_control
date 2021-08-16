
showLoader()
$(document).ready(function () {
    hideLoader();
    $('input').val('');

    $('#submit_login').click(function () {

        userdetail = $('#username').val();
        userpassword = $('#password').val();
        if (userdetail == '' || userdetail.length < 1) {
            iziToast.warning({
                title: 'Username Required',
                message: 'Enter a Valid Username',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        } else {

            if (userpassword == '' || userpassword.length < 1) {
                iziToast.error({
                    title: 'Password Required',
                    message: 'Enter a Valid Password',
                    position: 'bottomCenter',
                    timeout: 10000,
                    closeOnClick: true,
                    displayMode: 'replace'

                });
            } else {
                console.log("Attempting login");
                $.post("controller/login.php", {
                    userlogin: 'userlogin',
                    username: userdetail,
                    password: userpassword
                }, function (data) {
                    console.log(data);
                    $('.processing').html("");

                    if (data == 'SUCCESS') {
                        iziToast.success({
                            title: 'Login Success',
                            message: 'Redirecting you to your Dashboard',
                            position: 'bottomCenter',
                            timeout: 10000,
                            closeOnClick: true,
                            displayMode: 'replace'

                        });
                        $('#submit_login').val('Logged In');
                        $('#submit_login').attr('disabled', 'true').text('Logged In');

                        function reload() {

                            var url = window.location.href;
                            if (url.indexOf("localhost") > 0) {
                                window.location.href = "index";

                            } else {
                                window.location.href = "index";
                            }

                        }

                        setTimeout(reload, 1800);
                    } else if (data == 'USER_NOT_FOUND') {

                        iziToast.error({
                            title: 'Login Failed ',
                            message: 'Please Check your Username',
                            position: 'bottomCenter',
                            timeout: 10000,
                            closeOnClick: true,
                            displayMode: 'replace'

                        });
                    } else if (data == 'setup-in-progress') {

                        iziToast.info({
                            title: 'Dice Account Setup in Progress',
                            message: 'The Dice is Currently being Setup. Please check back later or consult with the System        Administrator.',
                            position: 'bottomCenter',
                            timeout: 10000,
                            closeOnClick: true,
                            displayMode: 'replace'

                        });
                    }
                 else if (data == 'FAILP') {

                    iziToast.error({
                        title: 'Login Failed ',
                        message: 'Please Check your Password',
                        position: 'bottomCenter',
                        timeout: 10000,
                        closeOnClick: true,
                        displayMode: 'replace'

                    });
                }  else if (data == 'INACTIVE') {
                        iziToast.error({
                            title: 'Account Deactivated ',
                            message: 'This Account has been deactivated. Contact the System Administrator for more Information.',
                            position: 'bottomCenter',
                            timeout: 10000,
                            closeOnClick: true,
                            displayMode: 'replace'

                        });
                    }else{
                        iziToast.error({
                            title: 'Other Message ',
                            message: data,
                            position: 'bottomCenter',
                            timeout: 10000000,
                            closeOnClick: true,
                            displayMode: 'replace'

                        });
                    }

                })
            }
        }

    })

    $('#g_sign_inBtn').click(function () {
        $('#g_signIn *').trigger('click');


    })


});
function renderButton() {
    gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
    });
}
function onSignIn(googleUser) {
    showLoader()
    signOut();
 console.log(googleUser)
        var id_token = googleUser.getAuthResponse().id_token;
    console.log(id_token)
    $.post("controller/login.php", {
        userlogin_gmail: 'userlogin_gmail',
        token:id_token

    }, function (data) {
        googleUser.disconnect()

console.log(data);
        if (data == 'success') {
            iziToast.success({
                title: 'Login Success',
                message: 'Redirecting you to your Dashboard',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
            $('#submit_login').val('Logged In');
            $('#submit_login').attr('disabled', 'true').text('Logged In');

            function reload() {

                var url = window.location.href;
                if (url.indexOf("localhost") > 0) {
                    window.location.href = "index";

                } else {
                    window.location.href = "index";
                }

            }

            setTimeout(reload, 1800);
        } else if (data == 'fail') {

            iziToast.error({
                title: 'Login Failed ',
                message: 'Sorry, the Email has not been authorised to access the system. Contact the Administrator.',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        } else if (data == 'setup-in-progress') {

            iziToast.info({
                title: 'Dice Account Setup in Progress',
                message: 'The Dice Linked to this Email is Currently being Setup. Please check back later or consult with the System        Administrator.',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        }
        else if (data == 'failp') {

            iziToast.error({
                title: 'Login Failed ',
                message: 'Please Check your Password',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        }
        else if (data == 'fatal_error') {

            iziToast.error({
                title: 'Error Occured ',
                message: 'A serious Error Occurred with the Google Authentication Plugin.Contact Administrator.',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        }  else if (data == 'inactive') {
            iziToast.error({
                title: 'Account Deactivated ',
                message: 'Contact Support/Administrator',
                position: 'bottomCenter',
                timeout: 10000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        }else{
            iziToast.error({
                title: 'Other Message ',
                message: data,
                position: 'bottomCenter',
                timeout: 10000000,
                closeOnClick: true,
                displayMode: 'replace'

            });
        }
    })

}


function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
    });
}
