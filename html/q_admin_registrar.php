<?php
include("config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$fullname = $_SESSION['fullname'];

// Update the queues that have been active for over 12 hours to expired status
$dateTimeNow = date("Y-m-d H:i:s");
$expiredQueueSql = "UPDATE q_registrar SET status = 'expired' WHERE status = 'active' AND time <= DATE_SUB('$dateTimeNow', INTERVAL 720 MINUTE)";
mysqli_query($conn, $expiredQueueSql);

// Fetch the number of active queues
$sqlNumQueues = "SELECT COUNT(*) AS num_queues FROM q_registrar WHERE status = 'active' AND s_stat = 'waiting'";
$resultNumQueues = mysqli_query($conn, $sqlNumQueues);

$numQueues = 0;
if ($resultNumQueues && mysqli_num_rows($resultNumQueues) > 0) {
    $row = mysqli_fetch_assoc($resultNumQueues);
    $numQueues = $row['num_queues'];
}




// Retrieve the smallest queue number that is active and waiting to display as the current queue
$sqlcurrent = "SELECT MIN(priority_queue_number) AS minimum FROM q_registrar WHERE status = 'active' AND s_stat = 'waiting'";
$resultcurrent = mysqli_query($conn, $sqlcurrent);

$currentQUEUE = 0;

if ($resultcurrent && mysqli_num_rows($resultcurrent) > 0) {
    $result = mysqli_fetch_assoc($resultcurrent);
    $currentQUEUE = $result['minimum'];
}





// Calculate the next queue number
$nextQUEUE = $currentQUEUE + 1;

if($currentQUEUE == 0){
  $currentQUEUE = 0;
  $nextQUEUE = 0;
}






//SERVEQ FUNCTIONS
if(isset($_POST['serve'])){
    // Update all 'Nserved' to 'waiting' status
    $sqlUpdateNserved = "UPDATE q_registrar SET s_stat = 'waiting' WHERE s_stat = 'Nserved'";
    if(mysqli_query($conn, $sqlUpdateNserved)){
        // i serve nija ang next person sa line
        $sqlServeNext = "UPDATE q_registrar SET s_stat = 'served',status = 'expired' WHERE s_stat = 'waiting' AND priority_queue_number = '$currentQUEUE'";
        if(mysqli_query($conn, $sqlServeNext)){
            header("Location: q_admin_registrar.php");
            exit();
        } else {
            echo "ERROR serving next in line";
        }
    } else {
        echo "ERROR updating 'Nserved' to 'waiting'";
    }
}






//NEXTQ BUTTON FUNCTIONS
if(isset($_POST['nextq'])){
    $sqlN_served = "UPDATE q_registrar SET s_stat = 'Nserved' WHERE priority_queue_number = '$currentQUEUE'";
    if(mysqli_query($conn,$sqlN_served)){
        header("Location: q_admin_registrar.php");
        exit();
    }else{
        echo "ERROR";
    }
}

?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>QUEUING_SYSTEM</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
          <a href="q_admin.php" class="app-brand-link">
              <span class="app-brand-logo demo">
                  <img src="image/SLSU_SEAL.ico" alt="New Logo" style="width: 50px; height: 50px;">
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2" style="color: #0c3bf7;">SLSU</span>
          </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <ul class="menu-inner py-1">
                <!-- Dashboards -->
                <li class="menu-item">
                  <a
                    href="q_admin.php"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Support">DASHBOARD</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="q_admin_cashier.php"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div data-i18n="Documentation">CASHIER</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="q_admin_registrar.php"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div data-i18n="Documentation">REGISTRAR</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a
                    href="q_admin_sas.php"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-chat"></i>
                    <div data-i18n="Documentation">SAS</div>
                  </a>
                </li>
              </ul>

            
            
        
            
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse" >
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-medium d-block"><?php echo $admin_id?></span>
                            <small class="text-muted">ADMIN: <?php echo $fullname ?></small><br>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle ms-1">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="q_admin_logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h1>REGISTRAR</h1>
              
              <div class="row">
                <!-- Order Statistics -->
              
                <div class="col-md-6 col-xl-5">
                    <div class="card bg-dark border-0 text-white" style="margin-left: 50px;">
                      <img class="card-img" src="../assets/img/elements/11.jpg" alt="Card image" />
                      <div class="card-img-overlay">
                        <h5 class="card-title">CURRENT QUEUE</h5>
                        <center><h1 style="font-size: 125px; color: rgb(255, 255, 255); text-shadow: 15px 15px 15px rgba(0, 0, 0, 0.5);"><?php echo $currentQUEUE ?></h1></center>
                        
                      </div>
                      <button type="button" class="btn btn-dark">SAS</button>
                    </div>
                  </div>
  
                  <div class="col-md-6 col-xl-5">
                    <div class="card bg-dark border-0 text-white" style="margin-left: 50px;">
                      <img class="card-img" src="../assets/img/elements/11.jpg" alt="Card image" />
                      <div class="card-img-overlay">
                        <h5 class="card-title">NEXT QUEUE</h5>
                        <center><h1 style="font-size: 125px; color: rgb(255, 255, 255); text-shadow: 15px 15px 15px rgba(0, 0, 0, 0.5);"><?php echo $nextQUEUE ?></h1></center>
                      </div>
                      <button type="button" class="btn btn-dark">SAS</button>
                    </div>
                  </div>
                  
                  <form style="text-align: center;" action="q_admin_registrar.php" method="post">
                    <button type="submit" name="nextq" value="nextq" class="btn btn-dark" style="margin-right: 180px;" <?php if ($numQueues == 1) echo 'disabled'; ?>>NEXT QUEUE</button><br>
                    <button type="submit" name="serve" value="serve" class="btn btn-primary" style="margin-right: 180px;">SERVED</button>
                  </form> 
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made with ❤️ by
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-medium">ThemeSelection</a>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
