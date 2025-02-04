const MSSQL = require("msnodesqlv8");
const odbcConnection = require("odbc");

const express = require("express");
const cors = require("cors");
const bodyParser = require("body-parser");
const app = express();
app.use(cors());
app.use(express.json());

const connectionString =
  "Driver={ODBC Driver 17 for SQL Server};Server=SAJIB-OAHID\\SQLEXPRESS01; Database=TailorShopManagement; Trusted_Connection=Yes;";

odbcConnection.connect(connectionString, (err, connection) => {
  if (err) {
    console.error("ODBC Connection failed:", err);
  } else {
    console.log("Connected via ODBC!");
    connection.close();
  }
});

const sentHttpResponse = (error, result, response, responseMessage) => {
  if (error) {
    return response.status(500).send({
      message: error.message,
      status: 500,
    });
  } else {
    return response.status(200).send({
      message: responseMessage,
      status: 200,
      data: result,
    });
  }
};

const dbOperation = (query, response, responseMessage = "") => {
  MSSQL.query(connectionString, query, (err, result) => {
    sentHttpResponse(err, result, response, responseMessage);
  });
};

app.listen(3001, () => {
  console.log("Port Running on 3001");
});

app.get("/customers/get", (req, res) => {
  const query = `select * from Customers`;
  dbOperation(query, res);
});
app.get("/customers/get/:id", (req, res) => {
  const id = req.params.id;
  const query = `select * from Customers where Id = ${id}`;
  dbOperation(query, res);
});

app.post("/customers/insert", (req, res) => {
  const data = req.body;
  const query = `insert into Customers (Name, GenderType, Email, Address, Phone)
                  values('${data.Name}','${data.GenderType}','${data.Email}','${data.Address}', '${data?.Phone}')  `;
  dbOperation(query, res, "Created");
});

app.put("/customers/update/:id", (req, res) => {
  const id = req.params.id;
  const data = req.body;
  const query = `update Customers set Name = '${data.Name}', GenderType = '${data.GenderType}', Email = '${data.Email}',
                 Address = '${data.Address}', Phone = '${data.Phone}' where Id = ${id}`;
  dbOperation(query, res, "Updated");
});

app.delete("/customers/delete/:id", (req, res) => {
  const id = req.params.id;
  const query = `delete Customers where Id = ${id}`;
  dbOperation(query, res, "Deleted");
});

app.get("/measurements/get", (req, res) => {
  const query = `select * from Measurements order by Id DESC`;
  dbOperation(query, res);
});

app.get("/measurements/get/:id", (req, res) => {
  const id = req.params.id;
  const query = `select * from Measurements where Id = ${id}`;
  dbOperation(query, res);
});

app.post("/measurements/insert", (req, res) => {
  const data = req.body;
  const query = `insert into Measurements(Name) values('${data.Name}')`;
  dbOperation(query, res, "Created");
});

app.put("/measurements/update/:id", (req, res) => {
  const id = req.params.id;
  const data = req.body;
  const query = `update Measurements set Name = '${data.Name}' where Id = ${id}`;
  dbOperation(query, res, "Updated");
});

app.delete("/measurements/delete/:id", (req, res) => {
  const id = req.params.id;
  const query = `delete Measurements where Id = ${id}`;
  dbOperation(query, res, "Deleted");
});

app.get("/products/get", (req, res) => {
  const query = `select * from Products order by Id DESC`;
  dbOperation(query, res);
});

app.get("/products/get/:id", (req, res) => {
  const id = req.params.id;
  const query = `select * from Products where Id = ${id}`;
  dbOperation(query, res);
});

app.post("/products/insert", (req, res) => {
  const data = req.body;
  const query = `insert into Products(Name) values('${data.Name}')`;
  dbOperation(query, res, "Created");
});

app.put("/products/update/:id", (req, res) => {
  const id = req.params.id;
  const data = req.body;
  const query = `update Products set Name = '${data.Name}' where Id = ${id}`;
  dbOperation(query, res, "Updated");
});

app.delete("/products/delete/:id", (req, res) => {
  const id = req.params.id;
  const query = `delete Products where Id = ${id}`;
  dbOperation(query, res, "Deleted");
});

app.get("/orderDetails/get", (req, res) => {
  const query = `select * from OrderDetails`;
  dbOperation(query, res);
});

app.get("/orderDetails/get/:id", (req, res) => {
  const id = req.params.id;
  const query = `select * from OrderDetails where Id = ${id}`;
  dbOperation(query, res);
});

app.post("/orderDetails/insert", (req, res) => {
  const data = req.body;
  const query = `insert into OrderDetails (OrderId,MeasurementsId,Length) 
                  values(${data.OrderId}, ${data.MeasurementsId}, ${data.Length})`;
  dbOperation(query, res, "Created");
});

app.put("/orderDetails/update/:id", (req, res) => {
  const id = req.params.id;
  const data = req.body;
  const query = `update OrderDetails set OrderId=${data.OrderId},MeasurementsId=${data.MeasurementsId},
                  Length=${data.Length}`;
  dbOperation(query, res, "Updated");
});

app.delete("/orderDetails/delete/:id", (req, res) => {
  const id = req.params.id;
  const query = `delete OrderDetails where Id = ${id}`;
  dbOperation(query, res, "Deleted");
});

app.get("/orders/get", (req, res) => {
  const query = `select od.*,c.Name As CustomerName,pd.Name as ProductName
                  from Orders od
                  left join Customers c on c.Id = od.CustomerId
                  left join Products pd on pd.Id = od.ProductId
                  order by od.Id DESC`;
  dbOperation(query, res);
});
app.get("/orders/get/:id", (req, res) => {
  const id = req.params.id;
  const query = `select od.*, c.Name,pd.Name as ProductName
                  from Orders od
                  left join Customers c on c.Id = od.CustomerId
                  left join Products pd on pd.Id = od.ProductId
                  where od.Id = ${id}
				          order by od.Id DESC;`;
  dbOperation(query, res);
});

const generateInvoiceNumber = (number) => {
  // Ensure the number is an integer
  const numericValue = parseInt(number, 10);

  if (isNaN(numericValue) || numericValue < 0) {
    throw new Error("Invalid input: Please provide a non-negative number.");
  }

  // Format the number with leading zeros (up to 6 digits)
  const formattedNumber = numericValue.toString().padStart(6, "0");

  // Add the prefix
  return `TLMS-${formattedNumber}`;
};

app.post("/orders/insert", (req, res) => {
  const data = req.body;
  data.DiscountAmount = !data?.DiscountAmount ? 0 : data?.DiscountAmount;
  data.HasDiscount = data?.DiscountAmount ? 1 : 0;
  const orderQuery = `insert into Orders(ProductId,CustomerId,IsPaid,HasAdvancePayment,AdvanceAmount,HasDiscount,DiscountType,DiscountAmount,InitialPrice,SubTotal,GrandTotal,OrderDate, DelivaryDate)
              values(${data.ProductId},${data.CustomerId},'0','0','0','${data.HasDiscount}','${data.DiscountType}',
              ${data?.DiscountAmount},0,${data.SubTotal},${data.GrandTotal}, '${data?.OrderDate}', '${data?.DelivaryDate}') 
              `;
  MSSQL.query(connectionString, orderQuery, (orderError, orderResult) => {
    if (orderError) {
      console.error(
        "Something went wrong while saving into Orders",
        orderQuery
      );
    }
    const selectLastInsertedId = `SELECT TOP 1 Id FROM Orders ORDER BY Id DESC;`;
    MSSQL.query(
      connectionString,
      selectLastInsertedId,
      (lastRowError, lastRowResult) => {
        if (lastRowError) {
          console.error("Something went wrong while fetching", query);
        }
        const currentInsertedOrderId = lastRowResult[0].Id;
        const invoiceNumber = generateInvoiceNumber(currentInsertedOrderId);
        const updateInvoiceQuery = `UPDATE Orders SET InvoiceNumber = '${invoiceNumber}' WHERE Id = '${currentInsertedOrderId}'`;
        MSSQL.query(
          connectionString,
          updateInvoiceQuery,
          (udpateInvoiceQueryError, udpateInvoiceQueryResult) => {
            if (udpateInvoiceQueryError) {
              console.error(
                "Something went wrong while updating invoice number",
                updateInvoiceQuery
              );
            }

            let detailArray = new Array();
            data.OrderDetails.forEach((detail) => {
              detailArray.push(
                `(${currentInsertedOrderId}, ${detail.measurementId}, '${detail.length}')`
              );
            });
            let orderDetailsQuery = `INSERT INTO OrderDetails(OrderId, MeasurementId, Length)
                                      VALUES ${detailArray.join(",")}`;
            dbOperation(orderDetailsQuery, res, "Order Created successfully");
          }
        );
      }
    );
  });
});

app.put("/orders/update/:id", (req, res) => {
  const id = req.params.id;
  const data = req.body;
  data.DiscountAmount = !data?.DiscountAmount ? 0 : data?.DiscountAmount;
  data.HasDiscount = data?.DiscountAmount ? 1 : 0;
  const orderQuery = `
      UPDATE Orders SET ProductId= ${data.ProductId}, CustomerId= ${data.CustomerId}, DiscountType='${data.DiscountType}', DiscountAmount=${data.DiscountAmount}, 
      SubTotal= ${data.SubTotal}, GrandTotal= ${data.GrandTotal}, OrderDate = '${data?.OrderDate}', DelivaryDate = '${data?.DelivaryDate}'
      WHERE Id = ${id}`;

  MSSQL.query(connectionString, orderQuery, (orderError, orderResult) => {
    if (orderError) {
      console.error(
        "Something went wrong while saving into Orders",
        orderQuery
      );
    }
    const orderDetailsDeleteQuery = `DELETE FROM OrderDetails WHERE OrderId = ${id}`;

    MSSQL.query(
      connectionString,
      orderDetailsDeleteQuery,
      (deleteError, deleteResult) => {
        if (deleteError) {
          console.error(
            "Something went wrong while saving into Orders",
            orderDetailsDeleteQuery
          );
        }
        let detailArray = new Array();
        data?.OrderDetails.forEach((detail) => {
          detailArray.push(
            `(${id}, ${detail.measurementId}, '${detail.length}')`
          );
        });

        let orderDetailsQuery = `INSERT INTO OrderDetails(OrderId, MeasurementId, Length)
                                  VALUES ${detailArray.join(",")}`;
        dbOperation(orderDetailsQuery, res, "Order Updated successfully");
      }
    );
  });
});

app.delete("/orders/delete/:id", (req, res) => {
  const id = req.params.id;
  const orderDetailsDeleteQuery = `DELETE FROM OrderDetails WHERE OrderId = ${id}`;

  MSSQL.query(
    connectionString,
    orderDetailsDeleteQuery,
    (detailsError, detailsResult) => {
      if (detailsError) {
        console.error(
          "Something went wrong while saving into Orders",
          orderDetailsDeleteQuery
        );
      }
      const orderDeleteQuery = `DELETE FROM Orders WHERE Id = ${id};`;
      dbOperation(orderDeleteQuery, res);
    }
  );
});

app.get("/order-details/getByOrderId/:id", (req, res) => {
  const id = req.params.id;
  const query = `select od.*, ms.Name 
                from OrderDetails od
                inner join Measurements ms on ms.Id = od.MeasurementId
                where od.OrderId = ${id}`;
  dbOperation(query, res);
});

app.get("/permonth/income/:startDate/:endDate", (req, res) => {
  // Parse the dates
  const startDate = new Date(req.params.startDate);
  const endDate = new Date(req.params.endDate);

  // Format dates to 'YYYY-MM-DD'
  const formattedStartDate = startDate.toISOString().split("T")[0];
  const formattedEndDate = endDate.toISOString().split("T")[0];

  const query = `
                SELECT 
                    OrderDate,
                    DAY(OrderDate) AS Day,        
                    SUM(GrandTotal) AS Total      
                FROM Orders
                WHERE OrderDate BETWEEN '${formattedStartDate}' AND '${formattedEndDate}' 
                GROUP BY OrderDate, DAY(OrderDate)     
                ORDER BY OrderDate, DAY(OrderDate);`;

  // Execute the query
  dbOperation(query, res);
});

app.get("/top/selling/product", (req, res) => {
  const query = `select pd.Name as productName, count(*) as topSellingPd
                from Orders
                left join Products pd on pd.Id = Orders.ProductId
                group by pd.Name
                order by topSellingPd desc`;
  dbOperation(query, res);
});
