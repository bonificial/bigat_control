$(document).ready(async function () {
    showLoader('Please Wait as we populate the Dashboard Stats.');
   await init();



    async function init() {

       await loadMembers();
        await loadEvents();
        await loadPosts();

    }

    function loadMembers() {

            response = $.ajax({
                type: "post",
                url: "controller/db_control.php",
                dataType: "json",
                async: false,
                data: {loadmembers: true},
                beforeSend: function () {
                    showLoader('Loading Members ..');
                },
                complete: function () {
                    hideLoader();
                },
                success: function (data) {
                   console.log(data)
                    $('#members_count').text(data.count)
                    $('#members_count_max').text(data.county_max)

                },
                error: function (data) {
                    showErrorToast('LM An Error Occured', data);

                }
            });

    }

    function loadEvents() {

            response = $.ajax({
                type: "post",
                url: "controller/db_control.php",
                dataType: "json",
                async: false,
                data: {loadEvents: true},
                beforeSend: function () {
                    showLoader('Loading Events..');
                },
                complete: function () {
                    hideLoader();
                },
                success: function (data) {
                    console.log(data)
                    $('#events_count').text(data.count)
                    $('#upcoming_event').html(data.nearest_event)

                },
                error: function (data) {
                    showErrorToast('LE An Error Occured', data);

                }

        })
    }

    function loadPosts() {

            response = $.ajax({
                type: "post",
                url: "controller/db_control.php",
                dataType: "json",
                async: false,
                data: {loadPosts: true},
                beforeSend: function () {
                    showLoader('Loading Posts ..');
                },
                complete: function () {
                    hideLoader();
                },
                success: function (data) {
                    console.log(data)
                    $('#posts_count').text(data.count)
                },
                error: function (data) {
                    showErrorToast('LP An Error Occured', data);

                }
            });

    }

});

