<form class="">
    <div class="position-relative row form-group">
        <label for="room_title" class="col-sm-2 col-form-label">Room Name</label>
        <div class="col-sm-8">
            <input name="room_key" hidden id="room_key" type="text"
                   class="form-control">
            <input name="room_name" id="room_name" placeholder="Enter the Room Name"
                   type="text"
                   class="form-control"/>
        </div>

    </div>



    <div class="position-relative row form-group">
        <label for="room_description" class="col-sm-2 col-form-label">Room Description</label>
        <div class="col-sm-10">
            <textarea name="text" id="room_description" class="form-control"></textarea>
        </div>
    </div>
    <div class="position-relative row form-group " id="fi_area" style="display: none">
        <label for="set_fi" class="col-sm-2 col-form-label">Set Featured Image</label>
        <div class="col-sm-10">
            <a href="#" target="_blank" id="featured_image_show_link"><img src="" id="featured_image_show" style="height: 75px"/> Click to View Full Image</a>
        </div>
    </div>


    <div class="position-relative row form-group">
        <label for="exampleFile" class="col-sm-2 col-form-label">Featured Image</label>
        <div class="col-sm-10">
            <input name="featured_image" id="featured_image" type="file"
                   class="form-control-file">
            <small class="form-text text-muted">Insert a Featured Image Here.</small>
        </div>
    </div>



</form>