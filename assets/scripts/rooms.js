
$(document).ready(async function () {
   await init();



    async function init() {
        loadRooms();

    }
    $('body').on("click", ".refreshRooms", async function () {
        await init();
    })

    function loadRooms(category = false) {

            response = $.ajax({
                type: "post",
                url: "controller/rooms.php",
                dataType: "text",
                async: true,
                data: {loadRooms: true},
                beforeSend: function () {
                    showLoader('Loading Rooms');
                },
                complete: function () {
                    hideLoader();
                },
                success: function (data) {
                  //  console.log(data);
                    $('#tbl_posts tbody').html(data);

                },
                error: function (data) {
                    showErrorToast('An Error Occured', data);

                }
            });

    }

    $('body').on("click", ".view_media", function () {
        window.open($('#post_media_link').val())
    })

    $('body').on("click", ".btn_view_room", async function () {

        $.ajax({
            type: "get",
            url: $(this).attr('data-link'),
            dataType: "json",
            async: true,
            data: {},
            beforeSend: function () {
                showLoader('Fetching Room Details');
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                console.log(data);
                if (data.length == 0) {
                    return showErrorToast('Room not found.', 'Room Details not Found. Try Reloading the Page')
                }

                $('#roomModal').text("Room Key  <" + data.key +">");

                $('#edit_room_key').val(data.key);
                $('#edit_room_name').val(  data.name);

                $('#edit_room_description').val(data.description);

                if(data.fi != ""){
                    $('#fi_area').css('display','block');
                    $('#edit_featured_image_show').attr('src', (data['fi']));
                    $('#edit_featured_image_show_link').attr('href', (data['fi']));
                    $('#edit_featured_image_show_link').removeAttr('disabled')
                }else{
                    $('#fi_area').css('display','none');
                    $('#edit_featured_image_show_link').attr('disabled', 'disabled');
                }


            },
            error: function (data) {
                response = data;

            }
        });






    })

    $('body').on("click", ".btn_close_parent_modal", function () {
        $(this).closest('.modal').modal('hide');

    });

    $('body').on("click", "#addRoom", function () {
      //  $('.iziToast').trigger('click');


        var form = new FormData();

        if (!ifInputFilled('room_name')) {
            showShortErrorToast("Valid Input Required", "You have not Entered a room name.");
            return 0;
        }
        if (!ifInputFilled('room_description')) {
            showShortErrorToast("Valid Input Required", "You have not Entered a room Description.");
            return 0;
        }
        form.append("addRoom", "true");

        form.append("room_name", $('#room_name').val());
        form.append("room_description", $('#room_description').val());
        var files = $('#edit_featured_image')[0].files[0];

        form.append('featured_image',files);


        var settings = {
            "url": "controller/rooms.php",
            "method": "POST",
            "timeout": 0,
            "dataType": "json",
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form,
            beforeSend: function () {
                showLoader('Adding Room..');
            },
            complete: function () {


            },
        };

        $.ajax(settings).done(function (response) {
            hideLoader();
            console.log(response)
            if(response.status == 'success'){
                showSuccessToast('Successful', 'Room Added Successfully');
            }
            if(response.status == 'error'){
                showErrorToast('Error', 'Room not Added due to a system error. Contact System admin.');
            }
            if(response.status == 'exists'){
                showErrorToast('Room Exists', 'Room already exists.');
            }


        });

    })

    $('body').on("click", "#delete_post", function () {
        obj['post_id'] =   $('#post_id').val();
        obj['pinPost'] = true;
        $.ajax({
            url: "controller/posts.php",
            type: 'post',
            dataType: 'json',
            async:false,
            data: obj,
            beforeSend: function () {
                showLoader('Pinning Post..');
            },
            complete: function () {
                hideLoader();

            },
            success: function (data) {

                message = data['response'];

                if (message.toLowerCase() == 'success') {
                    $("#submit_updates").attr('disabled', 'disabled').text('Refresh the Page or Press F5 ..');
                    showSuccessToast('Successful','Post was Added Successfully')
                }else{
                    showErrorToast('Error','An error occured')

                }
            },
            error:function (data){
                console.log(data)
            }
        });
    })

    $('body').on("click", "#submit_updates", function () {
        $('.iziToast').trigger('click');

        var form = new FormData();

        if (!ifInputFilled('edit_room_name')) {
            showShortErrorToast("Valid Input Required", "You have not Entered a room name.");
            return 0;
        }
        if (!ifInputFilled('edit_room_description')) {
            showShortErrorToast("Valid Input Required", "You have not Entered a room Description.");
            return 0;
        }
        form.append("editRoom", "true");
        form.append("room_key", $('#edit_room_key').val());
        form.append("room_name", $('#edit_room_name').val());
        form.append("room_description", $('#edit_room_description').val());
        var files = $('#edit_featured_image')[0].files[0];

        form.append('featured_image',files);


        var settings = {
            "url": "controller/rooms.php",
            "method": "POST",
            "timeout": 0,
            "dataType": "json",
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form,
            beforeSend: function () {
                showLoader('Editing Event..');
            },
            complete: function () {
                //hideLoader();

            },
        };

        $.ajax(settings).done(function (response) {
            hideLoader();
            console.log(response)
            if(response.response == 'success'){
                showSuccessToast('Successful', 'Room Edited Successfully');
            }
            if(response.response == 'error'){
                showErrorToast('Error', 'Room not Edited due to a system error. Contact System admin.');
            }
            if(response.response == 'exists'){
                showErrorToast('Room Exists', 'Room already exists.');
            }


        });

    })



});

