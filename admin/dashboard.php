<?php
    require('inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_approved_count'])) {
        $query = "SELECT COUNT(*) AS total FROM `bookings` WHERE `status` = 1"; // Count users with approved status
        $result = select($query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode(['count' => $row['total']]); // Echo the total count as JSON
        } else {
            echo json_encode(['count' => 0]); // Echo 0 if query fails
        }
        exit; // Exit to prevent further execution
    }

    $hname = 'localhost';
    $uname = 'root';
    $pass = '';
    $db = 'gymko';

    $con = mysqli_connect($hname, $uname, $pass, $db);

    if(!$con) {
        die("Cannot connect to database.".mysqli_connect_error());
    }

    // Execute SQL query to count appointments
    $appointment_result = mysqli_query($con, "SELECT COUNT(*) AS appointment_count FROM appointments");
    $appointment_row = mysqli_fetch_assoc($appointment_result);
    $appointment_count = $appointment_row['appointment_count'];

    // Execute SQL query to count patients
    $patient_result = mysqli_query($con, "SELECT COUNT(*) AS patient_count FROM user_cred");
    $patient_row = mysqli_fetch_assoc($patient_result);
    $patient_count = $patient_row['patient_count'];

    // Execute SQL query to count visitors' queries
    $query_result = mysqli_query($con, "SELECT COUNT(*) AS query_count FROM user_queries");
    $query_row = mysqli_fetch_assoc($query_result);
    $query_count = $query_row['query_count'];

    // Execute SQL query to count approved bookings
    $approved_booking_result = mysqli_query($con, "SELECT COUNT(*) AS booking_count FROM bookings WHERE `status` = 1");
    $approved_booking_row = mysqli_fetch_assoc($approved_booking_result);
    $approved_booking_count = $approved_booking_row['booking_count'];

    // Execute SQL query to count unapproved bookings
    $unapproved_booking_result = mysqli_query($con, "SELECT COUNT(*) AS booking_count FROM bookings WHERE `status` = 0");
    $unapproved_booking_row = mysqli_fetch_assoc($unapproved_booking_result);
    $unapproved_booking_count = $unapproved_booking_row['booking_count'];

    // Execute SQL query to count unapproved bookings
    $completed_booking_result = mysqli_query($con, "SELECT COUNT(*) AS booking_count FROM bookings WHERE `status` = 3");
    $completed_booking_row = mysqli_fetch_assoc($completed_booking_result);
    $completed_booking_count = $completed_booking_row['booking_count'];

    // Execute SQL query to count bookings dated today with status = 1
    $today_date = date('Y-m-d');
    $today_approved_booking_result = mysqli_query($con, "SELECT COUNT(*) AS booking_count FROM bookings WHERE `date` = '$today_date' AND `status` = 1");
    $today_approved_booking_row = mysqli_fetch_assoc($today_approved_booking_result);
    $today_approved_booking_count = $today_approved_booking_row['booking_count'];

    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="../css/common.css">

    <?php require('inc/links.php');?>

    <style>

        :root {
            --blue: #40534C;
            --blue-hover: #096066;
            --red: #ff0000;
        }

        .dashboard-box {
            padding: 20px;
            background-color: var(--blue);
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .dashboard-box:hover {
            background-color: var(--blue-hover);
        }

        .dashboard-link {
            text-decoration: none;
            color: inherit;
        }

        th{
            background-color: #40534C !important;
        }

    </style>

</head>
<body class="bg-light">

<?php require('inc/header.php');?>
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">DASHBOARD</h3>
            <div class="row">
                <!-- Today's Approved Booking Box -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="dashboard.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Today's Appointment</h5>
                            <i class="fa fa-calendar m-2" style="font-size:30px;"></i>
                            <p>Total Patients: <?php echo $today_approved_booking_count; ?></p>
                        </div>
                    </a>
                </div>

                <!-- Approved Booking Box -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="approved.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Approved</h5>
                            <i class="fa fa-book m-2" style="font-size:30px;"></i>
                            <p>Total Appointments: <?php echo $approved_booking_count; ?></p>
                        </div>
                    </a>
                </div>

                <!-- Completed Booking Box -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="completed.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Completed</h5>
                            <i class="fa fa-book m-2" style="font-size:30px;"></i>
                            <p>Total Appointments: <?php echo $completed_booking_count; ?></p>
                        </div>
                    </a>
                </div>

                <!-- Unapproved Booking Box -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="pending.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Pending</h5>
                            <i class="fa fa-book m-2" style="font-size:30px;"></i>
                            <p>Total Appointments: <?php echo $unapproved_booking_count; ?></p>
                        </div>
                    </a>
                </div>

                <!-- Patients Box -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="users.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Member</h5>
                            <i class="fa fa-user m-2" style="font-size:30px;"></i>
                            <p>Total Member: <?php echo $patient_count; ?></p>
                        </div>
                    </a>
                </div>

                <!-- Visitor Queries Box -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="user_queries.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Messages</h5>
                            <i class="fa fa-eye m-2" style="font-size:30px;"></i>
                            <p>Total Queries: <?php echo $query_count; ?></p>
                        </div>
                    </a>
                </div>
          
             <!-- Attendance Box -->
             <div class="col-lg-3 col-md-6 mb-4">
                    <a href="user_queries.php" class="dashboard-link">
                        <div class="dashboard-box">
                            <h5>Attendance</h5>
                            <i class="fa fa-eye m-2" style="font-size:30px;"></i>
                            <p>Total Attendance: 0</p>
                        </div>
                    </a>
                </div>
            </div>


            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-end mb-4">
                        <input type="text" oninput="search_user(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Search...">
                    </div>

                    <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead>
                                <tr class="bg-dark text-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone no.</th>
                                    <th scope="col" width="20%">Note</th>
                                    <th scope="col">Trainor</th>
                                    <th scope="col">Date</th>
                                    <th scope="col" width="8%">Time</th>
                                    <th scope="col" class="text-center">Status</th>   
                                    <th scope="col" class="text-center" width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="users-data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('inc/scripts.php'); ?>
<script src="scripts/dashboard.js"></script>
</body>
</html>
