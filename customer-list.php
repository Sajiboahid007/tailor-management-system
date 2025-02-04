<?php
include("./topbar.php");
include("./sidebar.php");
?>
<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <h2 class="text-center">Customer List</h2>
    </div>
    <div class="col-sm-4 text-end">
        <button id="CreateBtn" class="btn btn-success">
            <i class="fa fa-plus"></i> Add
        </button>
    </div>
</div>
<div>
    <table id="table" class="table table-hover table-bordered pt-5">
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="customerListId"></tbody>
        <tfoot></tfoot>
    </table>
</div>

<?php
include("./footer.php");
?>

<script>
    (function() {
        const buildHtml = (response) => {
            let htmlData = '';
            response.data?.forEach(item => {
                htmlData += `<tr>
                <td>${item.Name}</td>
                <td>${item.GenderType}</td>
                <td>${item.Email}</td>
                <td>${item.Address}</td>
                <td>${item.Phone}</td>
                <td>
                    <button class="btn btn-success editeId btn-sm" itemId='${item.Id}'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <button class="btn btn-danger deleteId btn-sm" itemId='${item.Id}'><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
                </tr>`
            })
            $("#customerListId").html(htmlData);
        }
        const addCreateForm = async () => {
            try {
                const resposne = await getAjax(`./customer-add.php`);
                openModal("Create Customer List", resposne)
            } catch (error) {
                errorMessage(error);
            }
        }

        const get = async () => {
            try {
                destroyDataTableIfExists("#table");
                const response = await getAjax(`${baseUrl}customers/get`);
                buildHtml(response);
                prepareDataTable("#table");
            } catch (error) {
                errorMessage(error);
            }
        }
        const save = async formData => {
            try {
                const response = await saveAjax(`${baseUrl}customers/insert`, formData);
                closeModal();
                get();
                successMessage();
            } catch (error) {
                errorMessage(error);
            }
        }

        const Delete = async (itemId) => {
            try {
                const response = await deleteAjax(`${baseUrl}customers/delete/${itemId}`);
                get();
                successMessage("Successfully Delete");
            } catch (error) {

            }
        }
        const update = async (formData) => {
            try {
                const resposne = await updateAjax(`${baseUrl}customers/update/${formData?.Id}`, formData);
                closeModal();
                get();
                successMessage("Successfully Updated")
            } catch (error) {

            }
        }

        //update 
        $("#updateItem").click(function() {
            const formData = getFormData('customerUpdate')
            update(formData);
        })

        //customers save
        $("#saveItem").click(function() {
            const formData = getFormData('customerAdd');
            save(formData);
        })
        //create customers
        $("#CreateBtn").click(function() {
            addCreateForm();
        })
        //delete section
        $(document).on("click", '.deleteId', async function() {
            const itemId = $(this).attr('itemId');
            const response = await deleteConfirmation();
            if (response) {
                Delete(itemId);
            }
        })
        //edite button
        $(document).on("click", ".editeId", async function() {
            const itemId = $(this).attr('itemId');
            const response = await getAjax(`${baseUrl}customers/get/${itemId}`);
            const updateData = await getAjax(`./customer-update.php`);
            openModal("Update Customer", updateData, true);
            setFormData('customerUpdate', response?.data)
        })
        //get customer list
        $(document).ready(function() {
            get();
        })
    })();
</script>