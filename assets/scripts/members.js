showLoader()

$(document).ready(async function () {
    await init();


    async function init() {
        loadMembers();

    }


    function loadMembers() {
        $.ajax({
            type: "post",
            url: "controller/db_control.php",
            dataType: "json",
            async: false,
            data: {loadmembers: true},
            beforeSend: function () {
                showLoader('Loading ..');
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                console.log(data)
                //    $('#members_count').text(data.count)
                $('#members_list tbody').html("");
                let count = 1;
                for (const [key, member] of Object.entries(data.members)) {
                    let user_name = `<span>${member.username}</span>`;
                    let profile_td = member.profile_pic  ? '<td>' +
                           user_name+' <img alt="" class="rounded-circle" src="' + member.profile_pic + '" width="50" height="50">  </td>' :" <td> " + user_name+"</td>";

                    let actions = `  <button type="button" class="btn btn-link btn_view_participant" title="View Post" data-link="${member.link}" data-toggle="modal"  data-target="#viewMemberModal"><b class="fa fa-eye"></b> </button>`;
                    $('#members_list tbody').append(`<tr ><td>${ parseInt(key)+1}</td>${profile_td }  <td>${member.county}</td> <td>${member.deviceToken ? ' <div class="badge badge-success">Approved</div>' : ' <div class="badge badge-warning">Pending</div>'}</td> <td>${actions}</td>`);
                    count++;
                }


            },
            error: function (data) {
                console.log(data)
                showErrorToast('An Error Occured', data);

            }
        });

    }


    $('body').on("click", "#disable_user", async function () {

        let nTitle = nBody = "";
        $('.input-required').removeClass('input-required');
        $('.iziToast').trigger('click');

        response = $.ajax({
            type: "post",
            "url": "controller/members.php",
            dataType: "json",
            async: true,
            data: {disableAccount:true,userID: $('#userID').val() },
            beforeSend: function () {
                showLoader('Attempting to Disable Member\'s Account');

            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                console.log(data);
                if(data.status == 'success'){
                    showSuccessToast('Success', data.message);
                }else{
                    showErrorToast('Error',data.message)
                }
            },
            error: function (data) {

                response = data;

            }
        });
    })

    $('body').on("click", "#sendNotif", async function () {

        let nTitle = nBody = "";
        $('.input-required').removeClass('input-required');
        $('.iziToast').trigger('click');
        if(ifInputFilled('nTitle')){
            nTitle = ifInputFilled('nTitle');
        }else{
          return  showErrorToast('Title Required','Please set a Notification Title')
        }
        if(ifInputFilled('deviceToken')){
            deviceToken = ifInputFilled('deviceToken');
        }else{
            return  showErrorToast('Device Token Required','Please set a Notification Device Token')
        }
        if(ifInputFilled('nBody')){
            nBody = ifInputFilled('nBody');
        }else{
            return  showErrorToast('Body Required','Please set a Notification Body')
        }

        /*
         $title = $data['title'];
    $body = $data['content'];
    $deviceToken = $data['deviceToken'];
    $type = $data['type'];
         */
        response = $.ajax({
            type: "post",
            "url": "controller/members.php",
            dataType: "json",
            async: true,
            data: {sendNotif:true,userID: $('#userID').val(), title:nTitle ,deviceToken:deviceToken, content:nBody,type:'GEN_NOT'  },
            beforeSend: function () {
                showLoader('Sending notification to Member');
                console.log('Send notification to: ',$('#userID').val() )
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                console.log(data);
if(data.status == 'success'){
    showSuccessToast('Success', 'The notification was sent to the Member\'s device.' +
        ' If their device token has not refreshed, then it will be displayed if/once they are connected to the internet.');
}else{
    showErrorToast('Error',data.message)
}
            },
            error: function (data) {

                response = data;

            }
        });
    })

    $('body').on("click", ".btn_view_participant", async function () {
        response = $.ajax({
            type: "get",
            url: $(this).attr('data-link'),
            dataType: "json",
            async: true,
            data: {},
            beforeSend: function () {
                showLoader('Fetching Member');
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                console.log(data);
                if (data.length == 0) {
                    return showErrorToast('Participant not found.', 'Participant Details not Found. Try Reloading the Page')
                }

                response = data
                $('#modal-title').text("Viewing " + data.username + "'s Details");
                $('#mName').val(data.username);
                $('#userID').val(data.key);
                $('#mEmail').val(data.email).css('font-size','13px')
                $('#mDOB').val(data.dob);
                $('#deviceToken').val(data.deviceToken);

                $('#mCounty').val(data.county);
                $('#map_modal').css('display','none');
  /*              if(data.location){
                    $('#loctitle').text(data.username + '\'s Last Logged Location (Approx +- 5 kms)')
                    geocode(data.location.latitude, data.location.longitude);
                    $('#view_location').removeAttr('disabled').addClass('btn-outline-info').removeClass('btn-outline-dark') ;
                }else{
                    $('#view_location').attr('disabled',true).addClass('btn-outline-dark').removeClass('btn-outline-info')

                    showShortErrorToast('Location not Logged', 'The Members device has not logged any location data.Probably they did not allow the BIGAT app to use location services.')
                }
*/
                $('#notifs').html("");
                if(data.deviceToken){
                    $('#notifs').append('  <div class="badge badge-success">Notifications Enabled</div>')
                }else{
                    $('#notifs').append('  <div class="badge badge-danger">Notifications Disabled</div>')
                }

                if(data.active ==false){
                    $('#enable_user').css('display','block');
                    $('#disable_user').css('display','none');
$('#notifs').append('  <div class="badge badge-danger">User Inactive</div>')
                }else{
                    $('#notifs').append('  <div class="badge badge-success">User Active</div>')
                    $('#enable_user').css('display','none');
                    $('#disable_user').css('display','block');
                }
            },
            error: function (data) {

                response = data;

            }
        });
        return response;


    })

   async function fetchData(datalink, data) {
        response = null;

    }
    function geocode(lat,lng){
        //
        $.ajax({
            type: "get",
            url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyCX0chvrZAprlquX-pSM9cWzR2If62ZLHU`,
            dataType: "json",
            async: true,
            data: {},
            beforeSend: function () {
                showLoader('Geocoding Location');
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                console.log(data);
                $('#lastLoc').val(data.results[5].formatted_address);
                initMap(lat,lng);
            },
            error: function (data) {
showErrorToast(data)

            }
        });
    }
    function initMap(lat, lng) {
        // The location of Uluru
        const uluru = { lat:lat, lng: lng };
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
            center: uluru,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: 9
        });

        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
            position: uluru,
            map: map,
        });
    }

});

// Get the modal
var modal = document.getElementById("map_modal");

// Get the button that opens the modal
var btn = document.getElementById("view_location");

// Get the <span> element that closes the modal
var span = document.getElementById("close_map");

// When the user clicks on the button, open the modal
btn.onclick = function() {

    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}