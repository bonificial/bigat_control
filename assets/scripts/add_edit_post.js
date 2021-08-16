showLoader()

$(document).ready(async function () {
   await init();
    hideLoader();


    async function init() {

        loadCategories();
    }



    function loadCategories() {
        categories = fetchData('controller/posts?getcategories', {});
        console.log(categories);
        $('#post_category').empty();
        if (categories.length != 0) {
            $.each(categories, function (key, value) {
                $('#post_category')
                    .append($("<option></option>")
                        .attr("value", value.id)
                        .text(value.category_name));
            });
        }
    }

    $('body').on("click", ".view_media", function () {
        window.open($('#post_media_link').val())
    })

    $('body').on("click", ".btn_view_post", function () {
        data = fetchData($(this).attr('data-link'), {})[0];
        console.log(data);
        if (data.length == 0) {
            return showErrorToast('Post not found.', 'Post Details not Found. Try Reloading the Page')
        }
        console.log(data);
        $('#postModal').text("Post #" + data["id"]);

        $('#post_id').val(data['id']);
        $('#post_title').val(data['post_title']);
        $('#post_subtitle').val(data['post_subtitle']);
        $('#post_category').val(data['post_category']);
        $('#post_type').val(data['post_type']);

        $('#post_media_link').val(data['post_media_link']);
        if (data['post_media_link'].length > 0) {
            $(".view_media").removeAttr('disabled');
        } else {
            $(".view_media").attr('disabled', 'disabled');
        }
        $('#post_content').val(data['post_content']);



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
                showLoader('Loading Post');
            },
            complete: function () {
                hideLoader();
            },
            success: function (data) {
                response = data


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

    $('body').on("click", "#add_category", function () {
        let category_name = "";
        iziToast.info({
            timeout: false,
            overlay: true,
            displayMode: 'once',
            id: 'inputs',
            zindex: 999,
            title: 'Add new category',
            message: 'Enter the Name of the New Category',
            position: 'center',
            drag: false,
            inputs: [

                ['<input type="text">', 'keyup', function (instance, toast, input, e) {
                 //   console.info(input.value);
                    category_name = input.value;
                }, true],

            ],
            buttons: [
                ['<button><b>Submit</b></button>', function (instance, toast) {
//console.log('adding',category);
                    $.ajax({
                        url: "controller/posts.php",
                        type: 'post',
                        dataType: 'json',
                        async:false,
                        data: {addCategory:true, category_name},
                        beforeSend: function () {
                            showLoader('Adding Category..');
                        },
                        complete: function () {
                            hideLoader();

                        },
                        success: function (data) {
                            console.log(data);
                            message = data['response'];

                            if (message.toLowerCase() == 'success') {
                                showSuccessToast('Successful','New Category Added Successfully')
                                loadCategories();
                                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            }
                            if (message.toLowerCase() == 'exists') {
                                showErrorToast('Category Exists','The category already Exists')
                                loadCategories();
                                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            }else{
                                showErrorToast('Error','An error occured')

                            }
                        },
                        error:function (data){
                            console.log(data)
                        }
                    });




                }, true],
                ['<button>Close</button>', function (instance, toast) {

                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                }],
            ],
        });
    })

    $('body').on("click", "#add_new_post", function () {
        //  $('.iziToast').trigger('click');
        obj = getforminputs();
        obj['addPost'] = true;
        obj['post_id'] =   $('#post_id').val();
        console.log(obj);
        if (obj['proceed'] == true) {
            $.ajax({
                url: "controller/posts.php",
                type: 'post',
                dataType: 'json',
                async:false,
                data: obj,
                beforeSend: function () {
                    showLoader('Adding New Post ..');
                },
                complete: function () {
                    hideLoader();

                },
                success: function (data) {
                    console.log(data);
                    message = data['response'];

                    if (message.toLowerCase() == 'success') {

                        showSuccessToast('Successful','Post was Added Successfully')
                    }else{
                        showErrorToast('Error','An error occured')

                    }
                },
                error:function (data){
                    console.log(data)
                }
            });
        }
    })

    $('body').on("click", "#submit_updates", function () {
      //  $('.iziToast').trigger('click');
        obj = getforminputs();
        obj['editPost'] = true;
        obj['post_id'] =   $('#post_id').val();
        console.log(obj);
        if (obj['proceed'] == true) {
            $.ajax({
                url: "controller/posts.php",
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
            });
        }
    })

    $('body').on("click", "#pin_post", function () {
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

                    showSuccessToast('Successful','Post was Edited Successfully')
                }else{
                    showErrorToast('Error','An error occured')

                }
            },
            error:function (data){
                console.log(data)
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
        function getforminputs() {
        var obj = {};
        var proceed = true;
        at = 'post_title';
        $('.input-required').removeClass('input-required');
        if (!ifInputFilled(at) || proceed != true) {
            proceed = false;
            showShortErrorToast("Valid Input Required", "You have not Entered a Post Title.");
        } else {
            obj[at] = ifInputFilled(at);
            at = 'post_category';
            if (!ifInputFilled(at) || proceed != true) {
                proceed = false;
                showShortErrorToast("Valid Input Required", "You have not set the Post Category.");
            } else {
                obj[at] = ifInputFilled(at);
                at = 'post_type';
                if (!ifInputFilled(at) || proceed != true) {
                    proceed = false;
                    showShortErrorToast("Valid Input Required", "You have not set the Post Type.");
                } else {
                    posttype = obj[at] = ifInputFilled(at);
                    if (posttype == 'Text') {
                        at = 'post_content';
                        if (!ifInputFilled(at) || proceed != true) {
                            proceed = false;
                            showShortErrorToast("Valid Input Required", "You have not provided the Post's text content.");
                        } else {
                            proceed = true;
                            obj[at] = ifInputFilled(at);
                        }
                    }
                    if (posttype == 'Video') {
                        at = 'post_media_link';
                        if (!ifInputFilled(at) || proceed != true) {
                            proceed = false;
                            showShortErrorToast("Valid Input Required", "You have not provided the Post's Video Link.");
                        } else {
                            proceed = true;
                            obj[at] = ifInputFilled(at);
                        }
                    }
                    if (posttype == 'Image') {
                        at = 'post_media_link';
                        if (!ifInputFilled(at) || proceed != true) {
                            proceed = false;
                            showShortErrorToast("Valid Input Required", "You have not provided the Post's Image Link.");
                        } else {
                            proceed = true;
                            obj[at] = ifInputFilled(at);
                        }
                    }
                }
            }
        }
        obj['proceed'] = proceed;
        return obj;
    }
});

