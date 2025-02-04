<form id="orderCreate">
    <div class="row">
        <div class="col-sm-6">
            <label>Cumstomer Name</label>
            <select class="form-control" name="CustomerId" id="CustomerId"></select>
        </div>
        <div class="col-sm-6">
            <label>Product Name</label>
            <select class="form-control" name="ProductId" id="ProductId" class="form-control"></select>
        </div>
        <div class="col-sm-4">
            <label for="">Order Date</label>
            <input type="date" name="OrderDate" class="form-control">
        </div>
        <div class="col-sm-4">
            <label for="">Delivary Date</label>
            <input type="date" name="DelivaryDate" class="form-control">
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
    </div>
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
        const getRequiredData = async () => {
            try {
                const customerList = await getAjax(`${baseUrl}customers/get`);
                const measurementList = await getAjax(`${baseUrl}measurements/get`);
                const productList = await getAjax(`${baseUrl}products/get`);

                makeDropdown(customerList, '#CustomerId');
                makeDropdown(measurementList, '#MeasurementId');
                makeDropdown(productList, '#ProductId');

            } catch (error) {
                errorMessage(error);
            }
        }
        const Save = async (formData) => {
            try {
                const response = await saveAjax(`${baseUrl}orders/insert`, formData);
                successMessage('Order Created Successfully');
                closeModal();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } catch (error) {
                errorMessage(error);
            }
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

        $("#saveItem").click(function() {
            const formData = getFormData('orderCreate');
            formData.OrderDetails = orderDetails;
            console.log(formData);
            Save(formData);
        })

        $(document).ready(function() {
            getRequiredData();
        })
    })();
</script>