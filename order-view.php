<form id="orderDetails">
    <input type="hidden" name="Id" id="orderId">
</form>

<div id="invoicePdf">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <h1>ILIYEEN</h1>
            <h3>Invoice <span id="invoice"></span></h3>
            <h5>Date: <span id="date"></span></h5>
            <p class="lead">Order Details</p>
        </div>
    </div>

    <!-- Invoice Header -->
    <div class="row" id="invoice-header">
        <div class="col-6">
            <h5>Customer Information</h5>
            <p><strong>Name: </strong> <span id="customerName"></span> </p>
            <p><strong>Mobile: </strong><span id="mobile"></span></p>
            <p><strong>Email: </strong><span id="email"></span> </p>
            <p><strong>Address: </strong><span id="address"></span> </p>
        </div>
        <div class="col-6 text-end">
            <h5>Order Information</h5>
            <p><strong>Order Date: </strong> <span id="orderDate"></span> </p>
            <p><strong>Delivary Date: </strong><span id="delivaryDate"></span></p>
            <p><strong>Product Name: </strong><span id="productName"></span></p>
        </div>
        <div class="col-12 text-center">
            <table class="table table-striped">
                <thead>
                    <th>SL</th>
                    <th>Measurement</th>
                    <th>Lenght</th>
                </thead>
                <tbody id="measurementBody"></tbody>
                <tfoot></tfoot>
            </table>
        </div>
        <div class="col-6">
            <p><strong>Advance Payment: </strong><span id="advancePayment"></span></p>
            <p><strong>Discount: </strong><span id="discount"></span></p>
            <p><strong>Discount Type: </strong><span id="discounttype"></span></p>
            <p><strong>Sub Total: </strong><span id="subTotal"></span></p>
            <p><strong>Grand Total: </strong><span id="grandTotal"></span></p>
        </div>

        <div class=" row">
            <div class="col-12 text-center mt-4">
                <p>Thank you for your purchase!</p>
                <p>For any inquiries, please contact us.</p>
                <p>Phone: 0199972773, 0189373763</p>
            </div>
        </div>

    </div>
</div>

<script>
    (function() {
        const measurementListBuid = (orderDetails) => {
            let html = '';
            let serialNumber = 1;
            orderDetails.forEach(item => {
                html += `
                    <tr>
                    <td>${serialNumber}</td>
                    <td>${item?.measurementName}</td>
                    <td>${item.length}</td>
                    </tr>
                `;
                serialNumber++;
            });
            $("#measurementBody").html(html);
        }

        const Get = async () => {
            try {
                const orderId = $("#orderId").val();
                const resposne = await getAjax(`${baseUrl}orders/get/${orderId}`);

                const orderInfo = resposne.data[0];
                const customerId = orderInfo.CustomerId;

                const customerList = await getAjax(`${baseUrl}customers/get/${customerId}`);
                const customer = customerList.data[0];
                const measurementList = await getAjax(`${baseUrl}order-details/getByOrderId/${orderInfo.Id}`);
                // console.log(measurementList);

                $("#customerName").text(customer.Name);
                $("#mobile").text(customer.Phone);
                $("#email").text(customer.Email);
                $("#address").text(customer.Address);
                //orderinfo
                $("#invoice").text(orderInfo.InvoiceNumber);
                $("#orderDate").text(getFormattedDateForInput(orderInfo.OrderDate));
                $("#delivaryDate").text(getFormattedDateForInput(orderInfo.DelivaryDate));
                $("#productName").text(orderInfo.ProductName);
                $("#advancePayment").text(orderInfo.AdvanceAmount);
                $("#discount").text(orderInfo.DiscountAmount);
                $("#discounttype").text(orderInfo.DiscountType);
                $("#subTotal").text(orderInfo.SubTotal);
                $("#grandTotal").text(orderInfo.GrandTotal);

                //measurement
                let orderDetails = new Array;

                const orderDetilsResponse = await getAjax(`${baseUrl}order-details/getByOrderId/${orderInfo.Id}`);
                orderDetilsResponse?.data?.forEach(detail => {
                    orderDetails.push({
                        measurementId: detail?.MeasurementId,
                        measurementName: detail?.Name,
                        length: detail?.Length,
                        itemId: generateUUID()
                    });
                });

                measurementListBuid(orderDetails);


            } catch (error) {
                errorMessage(error);
            }
        }

        $(document).ready(function() {
            // Get the current date
            const date = new Date();

            // Format the date as needed (e.g., MM/DD/YYYY or DD-MM-YYYY)
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Use jQuery to set the date in the HTML
            $("#date").text(formattedDate);
        });

        $(document).ready(function() {
            Get();
        })

        function printDiv(divId) {
            const printContents = document.getElementById(divId).innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            // Restore scripts and page behavior
            location.reload();
        }
        $('#printItem').click(function() {
            printDiv('invoicePdf');
        })

    }())
</script>