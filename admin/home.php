<h1 style="color: white; text-align: center;">Welcome back Admin to <?php echo $_settings->info('name') ?> Dashboard</h1>

<hr>
<div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-light elevation-1"><i class="fas fa-tags"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Products</span>
                <span class="info-box-number">
                  <?php 
                    $productCountQuery = $conn->query("SELECT COUNT(*) as total FROM products");
                    $productCount = $productCountQuery->fetch_assoc()['total'];
                    echo number_format($productCount);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">
                  <?php 
                    $pending = $conn->query("SELECT COUNT(id) as total FROM `orders` ")->fetch_assoc()['total'];
                    echo number_format($pending);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Clients</span>
                <span class="info-box-number">
                  <?php 
                    $pending = $conn->query("SELECT COUNT(id) as total FROM `clients` ")->fetch_assoc()['total'];
                    echo number_format($pending);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Sales (RM)</span>
                <span class="info-box-number">
                <?php 
                    $sales = $conn->query("SELECT sum(amount) as total FROM `orders`")->fetch_assoc()['total'];
                    echo number_format($sales);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>
        <!-- Sales Chart by Monthly and Weekly -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <canvas id="salesChart" width="400" height="200"></canvas><br>
        <div style="text-align: center;">
            <button id="toggleChartButton" onclick="toggleChartView()" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Toggle View For Weekly / Monthly Sales
            </button>
        </div>
        <script>
            // Sales Chart
            let isWeeklyView = true; // Default to weekly view

            // Function to toggle between weekly and monthly view
            function toggleChartView() {
                isWeeklyView = !isWeeklyView;
                fetchDataAndRefreshChart();
            }

            // Function to fetch data and refresh the chart
            function fetchDataAndRefreshChart() {
                const url = isWeeklyView ? 'fetch_weekly_sales_data.php' : 'fetch_monthly_sales_data.php';

                // Fetch sales data from the server using AJAX
                fetch(url) // Replace with the correct URL to your PHP file
                    .then(response => response.json())
                    .then(data => {
                        // Extract the data for the chart
                        const salesData = data.sales;

                        // Extract the labels and data from the fetched data
                        const labels = salesData.map(item => item.label);
                        const dataPoints = salesData.map(item => item.data);

                        // Get the canvas element
                        const ctx = document.getElementById('salesChart').getContext('2d');

                        // Create the chart
                        const chart = new Chart(ctx, {
                            type: 'bar', // You can change the chart type as needed (e.g., 'line', 'pie', etc.)
                            data: {
                                labels: labels, // Use the 'label' data
                                datasets: [
                                    {
                                        label: isWeeklyView ? 'Weekly Sales (RM)' : 'Monthly Sales (RM)',
                                        data: dataPoints, // Use the 'data' data
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Adjust the color as needed
                                        borderColor: 'rgba(75, 192, 192, 1)', // Adjust the color as needed
                                        borderWidth: 1,
                                    },
                                ],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                    },
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            
                                            color: 'white',
                                            textShadow: '2px 2px 4px rgba(0, 0, 0, 0.5)', // Add a shadow to the text
                                        }
                                    }
                                }
                            },
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching sales data:', error);
                    });
            }

            // Initially fetch and display data
            fetchDataAndRefreshChart();
        </script>
        <!-- Sales Chart by Monthly and Weekly End -->
        <br><br>

<div class="container">
  <?php 
    $files = array();
    $products = $conn->query("SELECT * FROM `products` order by rand() ");
    while($row = $products->fetch_assoc()){
      if(!is_dir(base_app.'uploads/product_'.$row['id']))
      continue;
      $fopen = scandir(base_app.'uploads/product_'.$row['id']);
      foreach($fopen as $fname){
        if(in_array($fname,array('.','..')))
          continue;
        $files[]= validate_image('uploads/product_'.$row['id'].'/'.$fname);
      }
    }
  ?>
  <div id="tourCarousel"  class="carousel slide" data-ride="carousel" data-interval="3000">
      <div class="carousel-inner h-100">
          <?php foreach($files as $k => $img): ?>
          <div class="carousel-item  h-100 <?php echo $k == 0? 'active': '' ?>">
              <img class="d-block w-100  h-100" style="object-fit:contain" src="<?php echo $img ?>" alt="">
          </div>
          <?php endforeach; ?>
      </div>
      <a class="carousel-control-prev" href="#tourCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#tourCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
      </a>
  </div>
</div>
