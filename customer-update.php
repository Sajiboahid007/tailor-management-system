<form id="customerUpdate">
    <input type="hidden" class="form-control" name="Id">
    <div class="row">
        <div class="col-sm-6">
            <label for="">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="Name" placeholder="Name">
            </div>
        </div>
        <div class="col-sm-6">
            <label for="">Gender Type</label>
            <div class="col-sm-10">
                <select name="GenderType" class="form-control">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <label for="">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="Email" placeholder="Email">
            </div>
        </div>
        <div class="col-sm-6">
            <label for="">Phone Number</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="Phone" placeholder="Phone">
            </div>
        </div>
        <div class="col-sm-12">
            <label for="">Address</label>
            <div class="col-sm-12">
                <textarea name="Address" class="form-control" col="3" placeholder="Address"></textarea>
            </div>
        </div>

    </div>
</form>