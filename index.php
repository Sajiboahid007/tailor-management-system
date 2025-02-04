<?php
include("./topbar.php");
include("./sidebar.php");
?>
<div class="row">
    <div class="col-md-9 justify-content-center">
        <div class="w-100 h-100 p-4 ">
            <canvas id="myChart" class="w-100" style="height: 400px;"></canvas>
        </div>
    </div>

    <div class="col-md-3 justify-content-center">
        <div class="w-100 h-100 p-4 ">
            <canvas id="mypicChart" class="w-100" style="height: 400px;"></canvas>
        </div>
    </div>

</div>
<div class="row">
    <h3 class="col-sm-8 text-center md-5">Sales Data for the Current Month</h3>
    <h3 class="text-end md-1">Top Selling Products</h3>
</div>
<?php
include("./footer.php");
?>
<script>
    (function() {
        const createChart = async (type) => {
            const ctx = document.getElementById('myChart');
            const incomeData = await incomePerDay();
            const keys = Array.from(incomeData.keys());
            const values = Array.from(incomeData.values());

            const borderColors = keys.map(() => getRandomColor());

            new Chart(ctx, {
                type: type, //line/doughnut//
                data: {
                    labels: keys, //['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Sales',
                        data: values,
                        backgroundColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    maintainAspectRatio: false,
                }
            });
        }

        const incomePerDay = async () => {
            try {
                const currentDate = new Date();
                const startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                const lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                const response = await getAjax(`${baseUrl}permonth/income/${startDate}/${lastDate}`);

                let incomeMonth = new Map();
                for (let i = 1; i <= lastDate.getDate(); i++) {
                    incomeMonth.set(i, 0);
                }
                response.data.forEach(item => {
                    incomeMonth.set(item.Day, item.Total);
                })
                return incomeMonth;

            } catch (error) {
                console.log(error);
            }
        }


        const pieChart = async (type) => {
            const ctx = document.getElementById('mypicChart');
            const topProducts = await topSelling();
            const ProductName = Array.from(topProducts.keys());
            const TotalProduct = Array.from(topProducts.values());

            const backgroundColors = ProductName.map(() => getRandomColor());

            new Chart(ctx, {
                type: type,
                data: {
                    labels: ProductName,
                    datasets: [{
                        label: '# of Votes',
                        data: TotalProduct,
                        backgroundColor: backgroundColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }


        const getRandomColor = () => {
            return `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`; // Generates random bright colors
        }
        const topSelling = async () => {
            try {
                const response = await getAjax(`${baseUrl}top/selling/product`);
                let topProduct = new Map();

                response.data.forEach(item => {
                    topProduct.set(item.productName, item.topSellingPd)
                })

                return topProduct;
            } catch (error) {

            }
        }


        $(document).ready(function() {
            createChart('bar');
            pieChart('doughnut');
        })
    })();
</script>