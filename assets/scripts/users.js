
hideLoader();
$(document).ready(async function () {
    await init();



    async function init() {
        cleanFace();
        loadUsers();

    }


    function loadUsers(category = false) {

        $.when(showLoader("Fetching Users List..")).then(function () {
            response = $.ajax({
                type: "post",
                url: "controller/administrator.php",
                dataType: "text",
                async: false,
                data: {loadUsers: true},
                beforeSend: function () {
                    showLoader("Fetching Users List..")
                },
                complete: function () {
                    hideLoader();
                },
                success: function (data) {
                    $('#tbl_users tbody').html(data);
                   //   console.log(data);
                },
                error: function (data) {
                    showErrorToast('An Error Occured', data);

                }
            });
        })

    }

    $("body").on("click", "#addUser", function () {
        cleanFace();

        obj = getinputs();

        console.log(obj);
        obj['addUser'] = true;
        showLoader('Adding User..');
        $.ajax({
            url: "controller/administrator.php",
            type: 'POST',
            dataType: 'text',

            data: obj,
            success: function (data) {
                //    console.log(data);
                hideLoader();
                if (data.toLowerCase() == 'success') {
                    email = $('[name="email"]').val();
                    showSuccessToast('Successfull', "The New User with Email " + obj['email'] + " has been added to the system.")
                    loadUsers();
                    var emailContent = "Hi,\n" +
                        "%0D%0A%0D%0A" +
                        "The Account for " + email + " has been Created Successfully. %0D%0A%0D%0A Login to BIGAT using the Signin with GMAIL Option..\n" +
                        "%0D%0A%0D%0A" +
                        "Email: " + email +
                        "%0D%0A%0D%0A" +
                        "Status: " + obj['status'] +
                        "%0D%0A%0D%0A" +

                        "Thank you."
                    window.open("mailto:" + email + "?subject=BIGAT Account &body=" + emailContent + "", '_blank');

                } else {
                    if (data.toLowerCase() == 'exists') {

                        showErrorToast('User Exists', "Sorry, another user with the provided details already Exists. Check the Email or Phone or Username.", true)


                    } else {
                        showErrorToast('Message', data);
                    }
                }
            }
        })

    })

    $("body").on("click", ".edit_user_link", function () {
        showLoader('Fetching User Data..');
        $("form").trigger("reset");
        $('#pwd_update').hide();

        cleanFace();
        datalink = "controller/administrator";

        response = $.ajax({
            type: "post",
            url: datalink,
            dataType: "json",
            async: true,
            data: {getUserJSON:true,email:$(this).data('email')},
            success: function (data) {
                //        console.log(data)
                hideLoader();

             console.log(data);
                $('[name="edit_fname"]').val([data['firstname']]);
                $('[name="edit_lname"]').val([data['lastname']]);

                $('[name="edit_email"]').val([data['email']]);

                $('[name="edit_phone"]').val([data['phone']]);
                $('[name="edit_userid"]').val([data['id']]);


                $('[name="edit_access_level"]').val([data['user_level']]);

                $('[name="edit_status"]').val([data['status'].toUpperCase()]);
            },
            error: function (data) {
                console.log(data);
                hideLoader();
            }
        });





    })

    $('.tab-switcher').click(function (){
        $("form").trigger("reset");
    })

    $("body").on("click", "#editUser", function () {
        cleanFace();
        obj = getinputsEdit();

          console.log(obj);
        obj['editUser'] = true;
        obj['userID'] = $('[name="edit_userid"]').val();
        showLoader('Editing User Details..');
        $.ajax({
            url: "controller/administrator.php",
            type: 'POST',
            dataType: 'text',

            data: obj,
            success: function (data) {
                //    console.log(data);
                hideLoader();
                if (data.toLowerCase() == 'success') {

                    showSuccessToast('Successfull', "Details updated Successfully." )
                    loadUsers();

                } else {
                    if (data.toLowerCase() == 'error') {

                        showErrorToast('Error', "Sorry, an Error Occured while Trying to Update the Details." + data, true)


                    } else {
                        showErrorToast('Message', data);
                    }
                }
            }, error: function (data) {
                hideLoader();
                showErrorToast('An Error Occured', data);

            }
        })

    })

    $("body").on("click", "#deleteUser", function () {
        cleanFace();
obj = {};
        obj['deleteUser'] = true;
        obj['userID'] = $('[name="edit_userid"]').val();
        console.log(obj);

        showConfirmToastDialog('Confirm Deleting the User','Are you Sure you want to Delete the User :  '+ $('#edit_fname').val() + " "+ $('#edit_lname').val() + '?. Please note this Action is irreversible, you may want to DEACTIVATE them instead by setting their Account Status to Inactive.',function (){
            showLoader('Deleting User  ..');
            $.ajax({
                url: "controller/administrator.php",
                type: 'POST',
                dataType: 'text',

                data: obj,
                success: function (data) {
                    console.log(data);
                    hideLoader();
                    if (data.toLowerCase() == 'success') {

                        showSuccessToast('Successfull', "User Deleted Successfully." )
                        loadUsers();

                    } else {
                        if (data.toLowerCase() == 'no_exists') {

                            showErrorToast('Error', "User Does not Exist." )


                        }
                        if (data.toLowerCase() == 'error') {

                            showErrorToast('Error', "Sorry, an Error Occured while Trying to Delete the User." + data, true)


                        } else {
                            showErrorToast('Message', data);
                        }
                    }
                }, error: function (data) {
                    hideLoader();
                    showErrorToast('An Error Occured', data);

                }
            })
        }, function (){} )





    })
    function getinputs() {

        var obj = {};
        at = 'fname';
        if (!ifInputFilled(at)) {

            showShortErrorToast("First Name Required", "Please enter the Users First Name.");
            throw new Error('at ' + at);
            return false;
        } else {
            obj[at] = ifInputFilled(at);
            at = 'lname';
            if (!ifInputFilled(at)) {

                showShortErrorToast("Last Name Required", "Please enter the Users Last Name.");
                throw new Error('at ' + at);
                return false;
            } else {
                obj[at] = ifInputFilled(at);


                    at = 'email';
                    if (!ifInputFilled(at)) {

                        showShortErrorToast("Email Required", "Please enter the Users Access Email.");
                        throw new Error('at ' + at);
                        return false;
                    } else {
                        obj[at] = ifInputFilled(at);
                        at = 'phone';
                        if (!ifInputFilled(at)) {

                            showShortErrorToast("Phone No. Required", "Please enter the Users Phone No.");
                            throw new Error('at ' + at);
                            return false;
                        } else {
                            obj[at] = ifInputFilled(at);

                                at = 'access_level';
                                if (!ifInputFilled(at)) {

                                    showShortErrorToast("Access Level Required", "Select an Access Level for the User.");
                                    throw new Error('at ' + at);
                                    return false;
                                } else {
                                    obj[at] = ifInputFilled(at);

                                     /*   at = 'pwd';
                                        if (!ifInputFilled(at)) {

                                            showShortErrorToast("Password not Set", "Please Generate a New User Password by Clicking the Generate Password Button.");
                                            throw new Error('at ' + at);
                                            return false;
                                        } else {
                                            obj[at] = ifInputFilled(at);*/

                                            at = 'status';
                                            if (!ifRadioChecked(at)) {

                                                showShortErrorToast("Status not Set", "Please Indicate the Initial Status of the User Account.");
                                                throw new Error('at ' + at);
                                                return false;
                                            } else {
                                                obj[at] = ifRadioChecked(at);

                                            }
                                        }
                                    }
                                }

            }
        }
        return obj;
    }

    function getinputsEdit() {

        var obj = {};
        at = 'edit_fname';
        if (!ifInputFilled(at)) {

            showShortErrorToast("First Name Required", "Please enter the Users First Name.");
            throw new Error('at ' + at);
            return false;
        } else {
            obj[at] = ifInputFilled(at);
            at = 'edit_lname';
            if (!ifInputFilled(at)) {

                showShortErrorToast("Last Name Required", "Please enter the Users Last Name.");
                throw new Error('at ' + at);
                return false;
            } else {
                obj[at] = ifInputFilled(at);


                at = 'edit_email';
                if (!ifInputFilled(at)) {

                    showShortErrorToast("Email Required", "Please enter the Users Access Email.");
                    throw new Error('at ' + at);
                    return false;
                } else {
                    obj[at] = ifInputFilled(at);
                    at = 'edit_phone';
                    if (!ifInputFilled(at)) {

                        showShortErrorToast("Phone No. Required", "Please enter the Users Phone No.");
                        throw new Error('at ' + at);
                        return false;
                    } else {
                        obj[at] = ifInputFilled(at);

                        at = 'edit_access_level';
                        if (!ifInputFilled(at)) {

                            showShortErrorToast("Access Level Required", "Select an Access Level for the User.");
                            throw new Error('at ' + at);
                            return false;
                        } else {
                            obj[at] = ifInputFilled(at);

                            /*   at = 'edit_pwd';
                               if (!ifInputFilled(at)) {

                                   showShortErrorToast("Password not Set", "Please Generate a New User Password by Clicking the Generate Password Button.");
                                   throw new Error('at ' + at);
                                   return false;
                               } else {
                                   obj[at] = ifInputFilled(at);*/

                            at = 'edit_status';
                            if (!ifRadioChecked(at)) {

                                showShortErrorToast("Status not Set", "Please Indicate the Initial Status of the User Account.");
                                throw new Error('at ' + at);
                                return false;
                            } else {
                                obj[at] = ifRadioChecked(at);

                            }
                        }
                    }
                }

            }
        }
        return obj;
    }


})