

<form class="form">
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="position-relative row form-group">
            <input type="text" value="" hidden name="edit_userid"  />
            <label for="" class="col-sm-4 col-form-label">First Name</label>
            <div class="col-sm-8">
                <input name="edit_fname" id="edit_fname" placeholder="Enter the Users First Name"
                       type="text"
                       class="form-control"/>

            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Last Name</label>
            <div class="col-sm-8">
                <input name="edit_lname" id="edit_lname" placeholder="Enter the Users Last Name"
                       type="text"
                       class="form-control"/>

            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8">
                <input name="edit_email" id="edit_email" placeholder="Enter the Users Email"
                       type="text"
                       class="form-control"/>

            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Phone</label>
            <div class="col-sm-8">
                <input name="edit_phone" id="edit_phone" placeholder="Enter the Users Phone"
                       type="text"
                       class="form-control"/>

            </div>
        </div>


      <!--  <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
                <input name="edit_password" id="edit_room_name" placeholder="Enter the Users Phone"
                       type="text"
                       class="form-control"/>

            </div>
        </div>-->

    </div>
    <div class="col-md-6 col-sm-6">


        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Access Level</label>
            <div class="col-sm-8">
                <select tabindex="8"   class="form-control"
                        title="Select Access Level for the User"
                        id="edit_access_level"
                        name="edit_access_level">
                    <option selected disabled>Access Level</option>
                    <option value="Data_Officer">Data Officer</option>
                    <option value="ADMIN"> Admin</option>

                </select>
            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Account Status</label>
            <div class="col-sm-8">
                <div tabindex="9" class="form-control">
                    <input type="radio" name="edit_status" value="ACTIVE" class=""> ACTIVE &nbsp;
                    <input type="radio" name="edit_status" value="INACTIVE" class=""> INACTIVE &nbsp;

                </div>
            </div>
        </div>


    </div>
</div>


</form>