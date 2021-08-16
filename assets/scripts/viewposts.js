showLoader()

$(document).ready(async function () {
    await init();
    hideLoader();


    async function init() {
        loadPosts();
        loadCategories();
    }


    function loadPosts(category = false) {
        $.when(showLoader("Processing..")).then(function () {
            response = $.ajax({
                type: "post",
                url: "controller/posts.php",
                dataType: "text",
                async: false,
                data: {loadPosts: true},
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

    function loadCategories() {
        categories = fetchData('controller/posts?getcategories', {});
        console.log(categories);
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

    $('body').on("click", ".btn_view_post",   function () {
       let  data = null;
         $.ajax({
            type: "get",
            url: $(this).attr('data-link'),
            dataType: "json",
            async: false,
            data: data,
            beforeSend: function () {
                showLoader('Loading Post');
            },
            complete: function () {
                hideLoader();
            },
            success: function (res) {
               data = res[0];


            },
            error: function (res) {
                data = res;

            }
        });

        console.log(data);
        if (data.length == 0) {
            return showErrorToast('Post not found.', 'Post Details not Found. Try Reloading the Page')
        }

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
                $('#post_title').val('test');

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

                    showSuccessToast('Successful','Post was Pinned Successfully')
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
                    showSuccessToast('Successful','Post was Deleted Successfully')
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
            at = 'post_subtitle';
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

