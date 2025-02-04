<form id="orderUpdateForm">
    <input type="hidden" class="form-control" name="Id" id="orderId">
    <div class="row">
        <div class="col-sm-6">
            <label>Customer Name</label>
            <select type="text" name="CustomerId" id="CustomerId" class="form-control"></select>
        </div>
        <div class="col-sm-6">
            <label>Product Name</label>
            <select type="text" name="ProductId" id="ProductId" class="form-control"></select>
        </div>
        <div class="col-sm-6">
            <label>Order Date</label>
            <input type="date" name="OrderDate" id="OrderDate" class="form-control">
        </div>
        <div class="col-sm-6">
            <label>Delivary Date</label>
            <input type="date" name="DelivaryDate" id="DelivaryDate" class="form-control">
        </div>
        <div style="margin-top: 20px;"></div>

        <table class="table table-hovar table-bordered">
            <thead>
                <tr>
                    <th>SLno.</th>
                    <th>Measurement Type</th>
                    <th>Length</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th></th>
                    <th><select class="form-control" id="MeasurementId"></select></th>
                    <th><input type="text" class="form-control" id="length"></th>
                    <th> <button class="btn btn-success btn-sm" id="addMeasurement"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody id="measurementDetails">
            </tbody>
        </table>

        <div class="row">
            <div class="col-sm-4">
                <label for="">Sub total</label>
                <input type="number" class="form-control" min="0" id="SubTotal" name="SubTotal" placeholder="Sub total amount">
            </div>
            <div class="col-sm-4">
                <label for="">Discount</label>
                <input type="number" class="form-control" min="0" id="DiscountAmount" name="DiscountAmount" placeholder="Total Discount">
            </div>
            <div class="col-sm-4">
                <label for="">Discount Type</label>
                <select class="form-control" name="DiscountType" id="DiscountType">
                    <option value="Flat">Flat</option>
                    <option value="Percentange">Percentange</option>
                </select>
            </div>
            <div class="col-sm-8">
                <label for="">Grand Total</label>
                <input readonly type="number" class="form-control" name="GrandTotal" id="GrandTotal" placeholder="Total Discount">
            </div>
        </div>
    </div>
</form>

<script>
    (function() {
        let orderDetails = new Array();
        const makeDropdown = (data, id) => {
            let html = '';
            data?.data?.forEach(item => {
                html += `<option value="${item.Id}">${item.Name}</option>`
            });

            $(id).html(html);
        }

        const buildDetails = (orderDetails) => {
            let html = '';
            let serialNumber = 1;
            orderDetails.forEach(item => {
                html += `
                    <tr>
                    <td>${serialNumber}</td>
                    <td>${item?.measurementName}</td>
                    <td>${item.length}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm measurementDetailsDelete " itemId="${item.itemId}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </td>
                    </tr>
                `;
                serialNumber++;
            });
            $("#measurementDetails").html(html);
        }


        const requiredData = async () => {
            const orderList = await getAjax(`${baseUrl}orders/get`);
            const customerList = await getAjax(`${baseUrl}customers/get`);
            const measurementList = await getAjax(`${baseUrl}measurements/get`);
            const ProductList = await getAjax(`${baseUrl}products/get`);
            makeDropdown(customerList, '#CustomerId');
            makeDropdown(measurementList, '#MeasurementId');
            makeDropdown(ProductList, '#ProductId');
            makeDropdown(orderList, 'Id');

            // set form data
            const orderId = $("#orderId").val();
            const response = await getAjax(`${baseUrl}orders/get/${orderId}`);
            setFormData('orderUpdateForm', response?.data);

            // setting order-details
            const orderDetilsResponse = await getAjax(`${baseUrl}order-details/getByOrderId/${orderId}`);
            orderDetilsResponse?.data?.forEach(detail => {
                orderDetails.push({
                    measurementId: detail?.MeasurementId,
                    measurementName: detail?.Name,
                    length: detail?.Length,
                    itemId: generateUUID()
                });
            });

            buildDetails(orderDetails);
        }


        const calculateGrandTotal = () => {
            const subTotal = $("#SubTotal").val() ?? 0;
            const discountAmount = $("#DiscountAmount").val() ?? 0;
            const discountType = $("#DiscountType").val();
            let grandTotal = 0.0;

            if (!subTotal || subTotal === 0) {
                $('#GrandTotal').val(0);
                return;
            }

            if (discountType === 'Flat') {
                grandTotal = subTotal - discountAmount;
            } else {
                grandTotal = subTotal - ((subTotal / 100) * discountAmount);
            }
            $('#GrandTotal').val(grandTotal.toFixed(2));
        }


        const Update = async (formData) => {
            try {
                const response = await updateAjax(`${baseUrl}orders/update/${formData?.Id}`, formData);
                successMessage('Order Updated Successfully');
                closeModal();

                setTimeout(() => {
                    window.location.reload();
                }, 1000);

            } catch (error) {
                errorMessage(error);
            }
        }

        $("#addMeasurement").click(function(event) {
            event.preventDefault();
            const length = $("#length").val();
            if (!length) {
                alert('Length cannot be empty');
                return;
            }
            const measurementId = $("#MeasurementId").val();
            const measurementName = $("#MeasurementId option:selected").text();
            const itemId = generateUUID();
            orderDetails.push({
                measurementId,
                measurementName,
                length,
                itemId
            });
            buildDetails(orderDetails);
            $("#length").val('');
        })

        $('#SubTotal').keyup(function() {
            calculateGrandTotal();
        })
        $('#DiscountAmount').keyup(function() {
            calculateGrandTotal();
        })
        $('#DiscountType').change(function() {
            calculateGrandTotal();
        })

        $(document).on('click', '.measurementDetailsDelete', function(event) {
            event.preventDefault();
            const itemId = $(this).attr('itemId');
            orderDetails = orderDetails.filter(item => item.itemId !== itemId);
            buildDetails(orderDetails);
        });

        $("#updateItem").click(function() {
            const formData = getFormData('orderUpdateForm');
            formData.OrderDetails = orderDetails;
            console.log(formData);
            Update(formData);
        })

        $(document).ready(function() {
            requiredData();
        })
    })()
</script>