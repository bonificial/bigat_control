$(function () {
    $('[data-toggle="popover"]').popover({ html : true})
})

    function showErrorToast(title, message) {
        iziToast.show({
            id: 'haduken',
            theme: 'dark',

            icon: 'icon-contacts',
            backgroundColor: 'rgb(45,37,27)',
            titleColor: 'rgb(255, 144, 19)',
            messageColor: 'rgb(248, 217, 198)',
            title: title,
            displayMode: 2,
            message: message,
            maxWidth: '500px',

            position: 'center',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            timeout: 80000,
            progressBarColor: 'rgb(248, 14, 89)',
            image: 'assets/images/logo_small.png',
            imageWidth: 80,
            closeOnClick: true,
            layout: 2,

            iconColor: 'rgb(0, 255, 184)'
        });
    }
    function showShortErrorToast(title, message) {
        iziToast.show({
            id: 'haduken',
            theme: 'dark',
            closeOnClick: true,
            icon: 'icon-contacts',
            backgroundColor: 'rgb(45,37,27)',
            titleColor: 'rgb(255, 144, 19)',
            messageColor: 'rgb(248, 217, 198)',
            title: title,
            displayMode: 2,
            message: message,
            maxWidth: '500px',
            imageWidth: 80,
            position: 'bottomCenter',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            timeout: 8000,
            progressBarColor: 'rgb(248, 14, 89)',
            image: 'assets/images/logo_small.png',

            layout: 2,

            iconColor: 'rgb(0, 255, 184)'
        });
    }

    function hideLoader() {
        $('.loader').hide()

    }
function cleanFace() {
    $('*').removeClass('input-required');

    $('.iziToast').trigger('click');
}
function showActionInProcess() {


    $('.actionLoader').show()
}

function showLoader(message) {
    hideLoader();
        if (typeof(message) != 'undefined') {
            $('.loader-msg').text(message);
        }

        $('.loader').show().css('display', 'flex')
    }
function showSideLoader(message) {
    hideLoader();
    if (typeof(message) != 'undefined') {
        $('.sideloader-msg').text(message);
    }

    $('.sideloader').show().css('display', 'flex')
}
function hideSideLoader() {
    $('.sideloader').hide()

}
function showConfirmToastDialog(title, text, proceed_, cancel) {

    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        image: 'assets/img/hope_logo.jpg',
        imageWidth: 80,
        icon: 'fa fa-paper-plane',
        maxWidth: '700px',
        id: 'question',
        zindex: 99999,
        title: title,
        message: text,
        position: 'center',
        buttons: [
            ['<button><b>Proceed</b></button>', function (instance, toast) {
                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                proceed_();

            }, true],
            ['<button>Cancel</button>', function (instance, toast) {

                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                cancel();
            }],
        ],
        onClosing: function (instance, toast, closedBy) {
            // console.info('Closing | closedBy: ' + closedBy);
        },
        onClosed: function (instance, toast, closedBy) {
            //console.info('Closed | closedBy: ' + closedBy);
        }
    });
}
    function showSuccessToast(title, message, reloadpage) {
        iziToast.show({
            id: 'haduken',
            theme: 'dark',

            icon: 'icon-contacts',
            backgroundColor: '#4b9155',
            closeOnClick: true,
            title: title,
            displayMode: 2,
            message: message,
            maxWidth: '500px',
            imageWidth: 80,
            position: 'center',
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX',
            timeout: false,

            image: 'assets/images/logo_small.png',

            layout: 2,

            iconColor: 'rgb(0, 255, 184)',
            onClosed: function () {
                if (reloadpage == true) {
                    location.reload() ;
                }
            }
        });
    }
    function ifInputFilled(input) {
        //$('[name="' + input + '"]').focus()
        inputValue = $.trim($('[id="' + input + '"]').val());

        if (!inputValue || inputValue == '') {

            $('[id="' + input + '"]').addClass('input-required');
//console.log("Value for " + input + " is " + inputValue)


            return false;

        } else {

            //console.log(radioInput + " value is : "+ $('input[name="'+ radioInput+'"]:checked').val());
            return $('[id="' + input + '"]').val();
        }


    }

function ifRadioChecked(radioInput) {

    checkedValue = $('input[name="' + radioInput + '"]:checked').val();

    if (!$('input[name="' + radioInput + '"]').is(":checked")) {
        $('input[name="' + radioInput + '"]').parent('div').addClass('input-required');
        selectParentLink(radioInput)

        return false;

    } else {

        // console.log(radioInput + " value is : "+ $('input[name="'+ radioInput+'"]:checked').val());
        return $('input[name="' + radioInput + '"]:checked').val();
    }


}