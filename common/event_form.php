<form class="">
    <div class="position-relative row form-group">
        <label for="event_title" class="col-sm-2 col-form-label">Event Title</label>
        <div class="col-sm-8">
            <input name="event_id" hidden id="event_id" type="text"
                   class="form-control">
            <input name="event_title" id="event_title" placeholder="Enter the Event Title"
                   type="text"
                   class="form-control">
        </div>
        <div class="col-sm-2">
            <div class="form-group form-check" style="align-items: center">
                <input title="This will send a notification to the Users Mobile Devices" type="checkbox" class="form-check-input" value="true" id="notify_users">
                <label class="form-check-label" for="notify_users">Notify Users</label>
            </div>
        </div>
    </div>
    <div class="position-relative row form-group">
        <label for="event_datetime" class="col-sm-2 col-form-label">Date and Time</label>
        <div class="col-sm-10">
            <input name="event_datetime" id="event_datetime"
                   placeholder="Enter the Date and Time of Event" type="datetime-local"
                   class="form-control">
        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="event_location" class="col-sm-2 col-form-label">Event
            Location - County</label>
        <div class="col-sm-10">
            <select class="form-control" name="event_county" id="event_county">
                <option selected disabled value="">Select County</option>
                <option value="All">All</option>
                <?php include './common/counties.php' ?>
            </select>

        </div>
    </div>

    <div class="position-relative row form-group">
        <label for="event_description" class="col-sm-2 col-form-label">Event Description</label>
        <div class="col-sm-10">
            <textarea name="text" id="event_description" class="form-control"></textarea>
        </div>
    </div>
    <div class="position-relative row form-group" id="fi_area">
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