showLoader()

$(document).ready(async function () {
   await init();
    hideLoader();


    async function init() {
        loadEvents();

    }


    function loadEvents(category = false) {
        $.when(showLoader("Processing..")).then(function () {
            response = $.ajax({
                type: "post",
                url: "controller/events.php",
                dataType: "text",
                async: false,
                data: {loadEvents: true},
                beforeSend: function () {
                    showLoader('Loading Posts');
                },
                complete: function () {
                    hideLoader();
                },
                success: function (data) {
                    $('#tbl_posts tbody').html(data);
                    // console.log(data);
                },
                error: function (data) {
                    showErrorToast('An Error Occured', data);

                }
            });
        })
    }

    $('body').on("click", ".view_media", function () {
        window.open($('#post_media_link').val())
    })
    $('body').on("click", ".btn_view_post", function () {
        showLoader('Loading Event');
        data = fetchData($(this).attr('data-link'), {})[0];
        console.log(data);
        if (data.length == 0) {
            return showErrorToast('Post not found.', 'Post Details not Found. Try Reloading the Page')
        }
        console.log(data);
        $('#postModal').text("Event #" + data["id"]);

        $('#event_id').val(data['id']);
        $('#event_title').val(data['event_title']);
        $('#event_datetime').val(data['date_time']);
        $('#event_county').val(data['county']);
        $('#event_description').val(data['description']);
        if(data['featured_image'] != ""){
            $('#fi_area').attr('hidden',false);
            $('#featured_image_show').attr('src', (data['featured_image']));
            $('#featured_image_show_link').attr('href', (data['featured_image']));
        }else{
            $('#fi_area').attr('hidden',true);

        }






    })

    function fetchData(datalink, data) {
        response = null;
        $.ajax({
            type: "get",
            url: datalink,
            dataType: "json",
            async: false,
            data: data,
            beforeSend: function () {
                showLoader('Loading Event');
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                response = data
                $('#event_title').val('test');

            },
            error: function (data) {
                response = data;

            }
        });
        return response;
    }

    $('body').on("click", ".btn_close_parent_modal", function () {
        $(this).closest('.modal').modal('hide');

    });



    $('body').on("click", "#add_event", function () {
      //  $('.iziToast').trigger('click');


       /* $.ajax({
            url: 'https://fcm.googleapis.com/fcm/send',
            type: 'post',
            dataType: 'json',
            async:false,

            headers: {
                'Content-Type': 'application/json',
                Authorization: `key=AAAAwWRzQe0:APA91bG18tJpEMmvEqRfrk6i36EgGiTlecSqIXzgUW826shqQ-iWXRSUs5pO-PTi3DvXG_LJJeBATZhCphzI3-NAIj-9RVIwcjV43SB2Oulv2kw8WJC43HPs_L4GAn_fQ4RybaBceUih`,
            },
            data: JSON.stringify(  {
                to: 'etz33TGST_S_VHMtC7x7xX:APA91bG5UURX76U1AdOGk2UxdG5mWfVSR8orYLhPof5sig3Z67iUmihij5SasgF1Y4VSV83gvVbuOO43sA_M4yljSrEDTH7lJMPKKawJVb5VzP3wxQqtfipIoXM77BeyK9rlxd3Qh4MN',
                priority: 'normal',
                data: {
                    experienceId: '@bonificial/BIGAT',
                    title: "\uD83D\uDCE7 You've got mail",
                    message: 'Hello world! \uD83C\uDF10',
                },
            }),
            beforeSend: function () {
                showLoader('Pinning Post..');
            },
            complete: function () {
                hideLoader();

            },
            success: function (data) {
console.log(data);
                message = data['response'];

                if (message.toLowerCase() == 'success') {

                    showSuccessToast('Successful','Post was Edited Successfully')
                }else{
                    showErrorToast('Error','An error occured')

                }
            },
            error:function (data){
                console.log(data)
            }
        });*/


      var obj = getforminputs();
if(obj['proceed'] == false) return 0;
let notify = $('#notify_users').prop('checked') ? '1' : '0';

        var form = new FormData();
        form.append("addEvent", "true");
        form.append('notify',notify);
        form.append("event_title", $('#event_title').val());
        form.append("date_time", $('#event_datetime').val());
        form.append("event_county", $('#event_county').val());
      /*  form.append("location_lat", $('#event_title').val());
        form.append("location_long", $('#event_title').val());*/
        form.append("description", $('#event_description').val());
        var files = $('#featured_image')[0].files[0];

        form.append('featured_image',files);


        var settings = {
            "url": "controller/events.php",
            "method": "POST",
            "timeout": 0,
            "dataType": "json",
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form,
            beforeSend: function () {
                showLoader('Adding Event..');
            },
            complete: function () {
                //hideLoader();

            },
        };

        $.ajax(settings).done(function (response) {

            console.log(response)
            if(response.response == 'success'){
                showSuccessToast('Successful', 'Event Added Successfully');
            }
            if(response.response == 'success_nfd'){
                showSuccessToast('Successful', 'Event added Successfully.Notification Sent');
            }
            if(response.response == 'success_no_nfd'){
                showSuccessToast('Successful', 'Event added Successfully.Notification not Sent');
            }
            if(response.response == 'error'){
                showErrorToast('Error', 'Event was not added due to a system error. Contact System admin.');
            }
            if(response.response == 'exists'){
                showErrorToast('Event Exists', 'Event already exists.');
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
        let notify = $('#notify_users').prop('checked') ? '1' : '0';
        obj = getforminputs();
obj['notify'] = notify;
        obj['event_id'] =   $('#event_id').val();
        console.log(obj);
        var form = new FormData();

        form.append("editEvent", "true");
        form.append("event_id", obj.event_id);
        form.append('notify',notify);
        form.append("event_title", obj.event_title);
        form.append("event_datetime", obj.event_datetime);
        form.append("event_county", obj.event_county);
        /*  form.append("location_lat", $('#event_title').val());
          form.append("location_long", $('#event_title').val());*/
        form.append("event_description", obj.event_description);
        var files = $('#featured_image')[0].files[0];

        form.append('featured_image',files);


        var settings = {
            "url": "controller/events.php",
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
                hideLoader();

            },
        };

        $.ajax(settings).done(function (response) {




            console.log(response)

            if(response.response == 'success'){

                showSuccessToast('Successful', 'Event edited Successfully');


            }
            if(response.response == 'success_nfd'){
                showSuccessToast('Successful', 'Event edited Successfully.Notification Sent');

            }
            if(response.response == 'success_no_nfd'){
                showSuccessToast('Successful', 'Event edited Successfully.Notification not Sent');

            }
            if(response.response == 'error'){
                showErrorToast('Error', 'Event was not edited due to a system error. Contact System admin.');
            }
            if(response.response == 'exists'){
                showErrorToast('Event Exists', 'Event Parameters not changed.');
            }




        });




/*
        //  $('.iziToast').trigger('click');
        obj = getforminputs();
        obj['editEvent'] = true;
        obj['event_id'] =   $('#event_id').val();
        console.log(obj);
        if (obj['proceed'] == true) {
            $.ajax({
                url: "controller/events.php",
                type: 'post',
                dataType: 'json',
                async:false,
                data: obj,
                beforeSend: function () {
                    showLoader('Submitting Updates..');
                },
                complete: function () {
                    hideLoader();

                },
                success: function (data) {

                    message = data['response'];

                    if (message.toLowerCase() == 'success') {

                        showSuccessToast('Successful','Post was Edited Successfully')
                    }else{
                        showErrorToast('Error','An error occured')

                    }
                },
                error:function (data){
                    console.log(data)
                }
            });
        }
*/
    })

        function getforminputs() {
        var obj = {};
        var proceed = true;
        at = 'event_title';
        $('.input-required').removeClass('input-required');
        if (!ifInputFilled(at) || proceed != true) {
            proceed = false;
            showShortErrorToast("Valid Input Required", "You have not Entered an Event Title.");
        } else {
            obj[at] = ifInputFilled(at);
            at = 'event_datetime';
            if (!ifInputFilled(at) || proceed != true) {
                proceed = false;
                showShortErrorToast("Valid Input Required", "You have not set the Event Date and Time.");
            } else {
                obj[at] = ifInputFilled(at);
                at = 'event_county';
                if (!ifInputFilled(at) || proceed != true) {
                    proceed = false;
                    showShortErrorToast("Valid Input Required", "You have not set the Event County.");
                } else {
                    obj[at] = ifInputFilled(at);

                        at = 'event_description';
                        if (!ifInputFilled(at) || proceed != true) {
                            proceed = false;
                            showShortErrorToast("Valid Input Required", "You have not set the Event Description Content.");
                        } else {
                            proceed = true;
                            obj[at] = ifInputFilled(at);
                        }


                }
            }
        }
        obj['proceed'] = proceed;
        return obj;
    }
});

