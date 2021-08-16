<form class="">
    <div class="position-relative row form-group">
        <label for="post_title" class="col-sm-2 col-form-label">Title</label>
        <div class="col-sm-10">
            <input name="post_id" hidden id="post_id"  type="text"
                   class="form-control">
            <input name="post_title" id="post_title" placeholder="Enter a Post Title" type="text"   class="form-control">
        </div>
    </div>
    <div class="position-relative row form-group">
        <label for="post_subtitle" class="col-sm-2 col-form-label">Subtitle</label>
        <div class="col-sm-10">
            <input name="post_subtitle" id="post_subtitle" placeholder="Enter a Post Subtitle Title" type="text"
                   class="form-control">
        </div>
    </div>
    <div class="position-relative row form-group">
        <label for="postCategory" class="col-sm-2 col-form-label">Assign to Category</label>
        <div class="col-sm-6">

            <select class="form-control" id="post_category">

            </select>
        </div>
        <div class="col-sm-4 text-right">
<button type="button" class="btn btn-info" id="add_category">Add Category</button>

        </div>
    </div>
    <div class="position-relative row form-group">
        <label for="postType" class="col-sm-2 col-form-label">Post Type</label>
        <div class="col-sm-10">
            <select class="form-control" id="post_type">
                <option value="Text">Text</option>
                <option value="Image">Image</option>
                <option value="Video">Video</option>
            </select>
        </div>
    </div>
    <div class="position-relative row form-group">
        <label for="post_type" class="col-sm-2 col-form-label">Video/Image Link</label>
        <div class="col-sm-10">
            <div class="input-group mb-3">
            <input type="text" id="post_media_link" class="form-control" placeholder="Link to Media File" aria-label="" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary view_media" data-toggle="modal" data-target="#media_modal" type="button" disabled>View</button>
            </div>
            </div>
        </div>

    </div>
    <div class="position-relative row form-group">
        <label for="post_content" class="col-sm-2 col-form-label">Post Content</label>
        <div class="col-sm-10">
            <textarea name="text" id="post_content" class="form-control"></textarea>
        </div>
    </div>


    <div class="position-relative row form-group">
        <label for="exampleFile" class="col-sm-2 col-form-label">Featured Image</label>
        <div class="col-sm-10">
            <input name="featured_image" id="featured_image" type="file" class="form-control-file">
            <small class="form-text text-muted">Insert a Featured Image Here.</small>
        </div>
    </div>


</form>

