<?php
include("config.php");

// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['A_username'] )) {
    header("Location: admin_loging.php");
    exit();
}
$name = $_SESSION['A_username'];
$section = $_SESSION['section'];  


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $type_class = $_POST['type_class'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];
    $year_section = $section;
    $status = "active";
    // Insert data into database
    $sql = "INSERT INTO class_schedule (type_class, subject, date,year_section,status) VALUES ('$type_class', '$subject', '$date','$year_section','$status')";

    if (mysqli_query($conn, $sql)) {
        echo "Quiz/Exam scheduled successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
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

    <title>CLASS SCHEDULES</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="image/SLSU_SEAL.ico" />

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

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
    <style>
      .material-box {
          border: 1px solid #007bff;
          border-radius: 10px;
          padding: 35px;
          margin-bottom: 20px;
          text-align: center;
          position: relative;
          font-size: 50px;
      }
      
      .inner-box {
          border: 5px solid #000000;
          border-radius: 5px;
          padding: 30px;
          margin-top: 15px;
          position: absolute;
          left: 10px;
          top: 10px;
      }
  </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
          <a href="admin_gateway.php" class="app-brand-link">
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
            

            <!-- Layouts -->
            <li class="menu-item">
              <a href="admin_gateway.php"  class="menu-link">
                <i class="menu-icon">
                  <img src="image/dashboard.png" alt="Support Icon">
                </i>
                <div data-i18n="Support">DASHBOARD</div>
              </a>
            </li>
            
            <li class="menu-item">
              <a href="admin_assessment.php"  class="menu-link">
                <i class="menu-icon">
                  <img src="image/bxs-calendar.png" alt="Support Icon">
                </i>
                <div data-i18n="Support">ASSESSMENT</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="admin_event.php"  class="menu-link">
                <i class="menu-icon">
                  <img src="image/event_S.png" alt="Support Icon">
                </i>
                <div data-i18n="Support">EVENT SCHEDULES</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="admin_class.php"  class="menu-link">
                <i class="menu-icon">
                  <img src="image/class_schedule.png" alt="Support Icon">
                </i>
                <div data-i18n="Support">CLASS SCHEDULES</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="admin_learning_materials.php"  class="menu-link">
                <i class="menu-icon">
                  <img src="image/learning_materials.png" alt="Support Icon">
                </i>
                <div data-i18n="Support">LEARNING MATERIALS</div>
              </a>
            </li>

            <li class="menu-item">
              <a href="add_admin.php"  class="menu-link">
                <i class="menu-icon">
                  <img src="image/manplus.png" alt="Support Icon">
                </i>
                <div data-i18n="Support">ADD ADMIN</div>
              </a>
            </li>
            
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

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              

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
                            <span class="fw-medium d-block"><?php echo $name?></span>
                            <small class="text-muted">ADMIN</small>
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
                        <span class="align-middle">Appointment</span>
                      </a>
                    </li>
                    
                    
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="admin_logout_gateway.php">
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
              <h4 class="py-3 mb-4"></h4>
              <br>
              <br>
              <div class="position-absolute top-20 start-50 translate-middle">
                <img src="image/SLSU_LOGO.png " alt="Assessment Image" class="img-fluid" style="max-width: 100%; max-height: 100%; margin-left: 200px;margin-bottom: 45px;">
              </div>
                      <div class="row">
                      <!-- Basic -->
                      <div class="container mt-5">
                      <br>
                      <h2 class="mb-3">CLASS SCHEDULE</h2>
                      <div class="row">
                      <div class="container">
                      <div id="addAdminForm" class="alert alert-primary floating-form" style="display: none;">
                <form class="p-3" action="admin_gateway.php" method="post">
                    <div class="form-group">
                        <label for="pin">Enter PIN:</label>
                        <input type="password" class="form-control" id="pin" name="pin" required>
                    </div>
                    <button type="submit" name="pins" value="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="title">TYPE OF CLASS:</label>
                            <input type="text" class="form-control" id="title" name="type_class">
                        </div>

                        <div class="form-group">
                            <label for="subject">SUBJECT:</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>

                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                      </div>

                  </div>


                  </div>
                </div>
                

                
                
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <section id="basic-footer">
                

                <footer class="footer bg-light">
                  <div
                    class="container-fluid d-flex flex-md-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-3">
                    <div>
                      <a
                        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/"
                        target="_blank"
                        class="footer-text fw-bold"
                        >SLSU</a
                      >
                      ©
                    </div>
                    <div>
                      <a href="https://www.facebook.com/profile.php?id=100067705916384" class="footer-link me-4" target="_blank">Facebook</a>
                      <a href="https://www.facebook.com/places/Things-to-do-in-Bontoc-Southern-Leyte-Philippines/109651922399858/" class="footer-link me-4">twitter</a>
                      <a href="https://www.facebook.com/places/Things-to-do-in-Bontoc-Southern-Leyte-Philippines/109651922399858/" class="footer-link me-4">Contact</a>
                      <a href="https://www.facebook.com/places/Things-to-do-in-Bontoc-Southern-Leyte-Philippines/109651922399858/" class="footer-link">Terms &amp; Conditions</a>
                    </div>
                  </div>
                </footer>
              </section>
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

    <script>
  document.addEventListener("DOMContentLoaded", function() {
    const addAdminLink = document.querySelector("a[href='add_admin.php']");
    const addAdminForm = document.querySelector("#addAdminForm");

    addAdminLink.addEventListener("click", function(event) {
      event.preventDefault(); // Prevent default link behavior
      addAdminForm.style.display = "block";
    });
  });
</script>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
