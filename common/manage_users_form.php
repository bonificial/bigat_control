

<form class="form">
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="position-relative row form-group">
            <input type="text" value="" hidden name="userid"  />
            <label for="" class="col-sm-4 col-form-label">First Name</label>
            <div class="col-sm-8">
                <input name="fname" id="fname" placeholder="Enter the Users First Name"
                       type="text"
                       class="form-control"/>

            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Last Name</label>
            <div class="col-sm-8">
                <input name="lname" id="lname" placeholder="Enter the Users Last Name"
                       type="text"
                       class="form-control"/>

            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8">
                <input name="email" id="email" placeholder="Enter the Users Email"
                       type="text"
                       class="form-control"/>

            </div>
        </div>

        <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Phone</label>
            <div class="col-sm-8">
                <input name="phone" id="phone" placeholder="Enter the Users Phone"
                       type="text"
                       class="form-control"/>

            </div>
        </div>


      <!--  <div class="position-relative row form-group">
            <label for="" class="col-sm-4 col-form-label">Password</label>
            <div class="col-sm-8">
                <input name="password" id="room_name" placeholder="Enter the Users Phone"
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
                        id="access_level"
                        name="access_level">
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
                    <input type="radio" name="status" value="ACTIVE" class=""> ACTIVE &nbsp;
                    <input type="radio" name="status" value="INACTIVE" class=""> INACTIVE &nbsp;

                </div>
            </div>
        </div>


    </div>
</div>


</form>