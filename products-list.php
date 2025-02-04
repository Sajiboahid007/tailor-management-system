<?php
include("./topbar.php");
include("./sidebar.php");
?>
<div class="row">
    <div class="col-sm-4">
        <h2>Procucts List</h2>
    </div>
    <div class="col-sm-4"></div>
    <div class="col-sm-4 text-end">
        <button class="btn btn-success" id="createbtn"><i class="fa fa-plus"></i>Add</button>
    </div>
</div>
<table id="table" class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody id="productsBody"></tbody>
</table>
<?php
include("./footer.php");
?>


<script>
    (function() {
        const buildHtml = async (response) => {
            let htmlData = '';
            response?.data.forEach(item => {
                htmlData += `<tr>
                    <td>${item.Name}</td>
                    <td class='text-end '>
                        <button class="btn btn-success editbtn btn-sm" itemId="${item.Id}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        <button class="btn btn-danger deletebtn btn-sm" itemId="${item.Id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </td>
                    </tr>`
            })
            $("#productsBody").html(htmlData)
        }


        const Get = async () => {
            try {
                destroyDataTableIfExists("#table");
                const response = await getAjax(`${baseUrl}products/get`);
                buildHtml(response);
                prepareDataTable("#table");
            } catch (error) {
                errorMessage(error);
            }
        }
        const CreateFrom = async () => {
            try {
                const response = await getAjax('./products-add.php');
                openModal("Create Product", response);
            } catch (error) {
                errorMessage(error);
            }
        }

        const Save = async (formData) => {
            try {
                const response = await saveAjax(`${baseUrl}products/insert`, formData);
                closeModal();
                Get();
                successMessage("Successfully Created");
                console.log(response);
            } catch (error) {
                errorMessage(error)
            }
        }

        const Delete = async (itemId) => {
            try {
                const response = await deleteAjax(`${baseUrl}products/delete/${itemId}`)
                Get();
                successMessage("Successfully Deleted");
            } catch (error) {
                errorMessage(error);
            }
        }

        const Update = async (formData) => {
            try {
                const response = await updateAjax(`${baseUrl}products/update/${formData?.Id}`, formData);
                closeModal();
                Get();
                successMessage("Successfully Updated")
            } catch (error) {
                errorMessage(error);
            }
        }

        $(document).on('click', '.editbtn', async function() {
            const itemId = $(this).attr('itemId');
            const response = await getAjax(`${baseUrl}products/get/${itemId}`);
            const updateData = await getAjax('./products-update.php')
            openModal("Edite Product Name", updateData, true);
            setFormData("updateProduct", response?.data);
        })

        $(document).on('click', '.deletebtn', async function() {
            const itemId = $(this).attr('itemId');
            const response = await deleteConfirmation();
            if (response) {
                Delete(itemId)
            }
        })

        $('#saveItem').click(function() {
            const formData = getFormData('CreateProduct');
            Save(formData);
        })
        $('#updateItem').click(function() {
            const formData = getFormData('updateProduct');
            Update(formData);
        })
        $('#createbtn').click(function() {
            CreateFrom();
        })
        $(document).ready(function() {
            Get();
        })
    })()
</script>