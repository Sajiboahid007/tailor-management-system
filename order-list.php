<?php
include("./topbar.php");
include("./sidebar.php");
?>
<div class="row">
    <div class="col-sm-4">
        <h2>Order List</h2>
    </div>
    <div class="col-sm-4"></div>
    <div class="col-sm-4 text-end">
        <button class="btn btn-success" id="addbtn"><i class="fa fa-plus"></i>Add</button>
    </div>
</div>
<table id="table" class="table table-hovar table-bordered">
    <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Order Date</th>
            <th>Customer Name</th>
            <th>Product Name</th>
            <th>Delivary Date</th>
            <th>Sub Total</th>
            <th>Grand Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="orderTbody"></tbody>
</table>
<?php
include("./footer.php");
?>

<script>
    (function() {

        const buildHtml = (response) => {
            let htmlData = '';
            response?.data.forEach(item => {
                htmlData += `
                <tr>
                    <td>${item.InvoiceNumber}</td>
                    <td>${GetFormattedDate(item.OrderDate)}</td>
                    <td>${item.CustomerName}</td>
                    <td>${item.ProductName}</td>
                    <td>${GetFormattedDate(item.DelivaryDate)}</td>
                    <td>${item.SubTotal}</td>
                    <td>${item.GrandTotal}</td>
                    <td>
                        <button class="btn btn-success btn-sm editbtn" orderId="${item.Id}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                        <button class="btn btn-primary btn-sm viewbtn" orderId="${item.Id}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        <button class="btn btn-danger btn-sm btnDelete" orderId="${item.Id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </td>
                </tr>`
            })
            $("#orderTbody").html(htmlData);
        }
        const addForm = async () => {
            try {
                const response = await getAjax(`./order-add.php`);
                openModal("Create Order", response);
            } catch (error) {
                errorMessage(error);
            }
        }

        const Get = async () => {
            try {
                destroyDataTableIfExists("#table");
                const resposne = await getAjax(`${baseUrl}orders/get`);
                buildHtml(resposne);
                prepareDataTable("#table");
            } catch (error) {
                errorMessage(error);
            }
        }

        const Delete = async (itemId) => {
            try {
                const response = await deleteAjax(`${baseUrl}orders/delete/${itemId}`);
                successMessage("Order Successfully Deleted")
                Get();
            } catch (error) {
                console.error(error);
                errorMessage(error);
            }
        }

        $(document).on('click', '.btnDelete',
            async function() {
                const itemId = $(this).attr('orderId');
                const response = await deleteConfirmation();
                if (response) {
                    Delete(itemId);
                }
            });

        $("#addbtn").click(function() {
            addForm();
        })

        $(document).on('click', '.editbtn', async function() {
            const itemId = $(this).attr('orderId');

            const updateData = await getAjax(`./order-update.php`);
            openModal("Update Order", updateData, true);
            setFormData('orderUpdateForm', {
                Id: itemId
            });
        });

        $(document).on('click', '.viewbtn', async function() {
            const itemId = $(this).attr('orderId');
            const orderView = await getAjax(`./order-view.php`);
            openModal("Order Details", orderView, false, true);
            setFormData('orderDetails', {
                Id: itemId
            });
        });


        $(document).ready(function() {
            Get();
        })
    })();
</script>