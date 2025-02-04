<?php
include("./topbar.php");
include("./sidebar.php");
?>
<div class="row">
    <div class="col-sm-4">
        <h2>Measurement</h2>
    </div>
    <div class="col-sm-4"></div>
    <div class="col-sm-4 text-end">
        <button class="btn btn-success" id="CreateId"><i class="fa fa-plus"></i>Add</button>
    </div>
</div>
<table id="table" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody id="measurementBody"></tbody>
</table>
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
                <td class="text-end">
                    <button class="btn btn-success btn-sm canEdit" btnEdit="${item.Id}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <button class="btn btn-danger btn-sm canDelete" deleteId="${item.Id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
                </tr>`

            })
            $("#measurementBody").html(htmlData);
        }

        const Get = async () => {
            try {
                destroyDataTableIfExists("#table");
                const response = await getAjax(`${baseUrl}measurements/get`);
                buildHtml(response);
                prepareDataTable("#table");
            } catch (error) {
                errorMessage(error)
            }
        }
        const createForm = async () => {
            try {
                const response = await getAjax(`./measurement-add.php`);
                openModal("Measurement", response)
            } catch (error) {
                errorMessage(error);
            }
        }

        const Save = async (formData) => {
            try {
                const response = await saveAjax(`${baseUrl}measurements/insert`, formData);
                closeModal();
                Get();
                successMessage("Successfylly Saved");
            } catch (error) {

            }
        }

        const Delete = async (itemId) => {
            try {
                const response = await deleteAjax(`${baseUrl}measurements/delete/${itemId}`);
                successMessage("Successfully Delete");
                Get();
            } catch (error) {

            }
        }

        const Update = async (formData) => {
            try {
                const resposne = await updateAjax(`${baseUrl}measurements/update/${formData?.Id}`, formData);
                closeModal();
                Get();
                successMessage("Successfully Updated")
            } catch (error) {

            }
        }

        // new DataTable('#table');

        $("#saveItem").click(function() {
            const formData = getFormData("CreateFormId");
            Save(formData);
        })

        $("#updateItem").click(function() {
            const formData = getFormData('updateMeasurement')
            Update(formData);
        })

        $('#CreateId').click(function() {
            createForm();
        })

        $(document).on('click', '.canDelete ', async function() {
            const itemid = $(this).attr('deleteId');
            const response = await deleteConfirmation();
            if (response) {
                Delete(itemid);
            }
        })

        $(document).on('click', '.canEdit', async function() {
            const itemId = $(this).attr('btnEdit');
            const response = await getAjax(`${baseUrl}measurements/get/${itemId}`);
            const updateData = await getAjax(`./measurement-update.php`);
            openModal("Update Measurement", updateData, true);
            setFormData('updateMeasurement', response?.data)
        })

        $(document).ready(function() {
            Get();
        })
    })()
</script>