<!--Created by Kevin Titus on 2022-07-21.-->
<!-- PHP intergration -->
<?php
// Start a session to get the employee number from the session and use it to get the first name, last name and account type of the user
session_start();
require_once('../includes/functions.inc.php'); // Include the functions.inc.php file which contains the functions used in this file
// Make a database connection
$conn = require '../includes/dbconfig.php'; // Include the dbconfig.php file which contains the database connection details

require_once '../includes/authentication.inc.php'; // Include the authentication.php file
// This is then used to display the correct name and account type in the navbar and also to check if the user is an admin or manager to display this page
$empNo = $_SESSION['empNo']; // Get the employee number from the session and store it in a variable called $empNo, this is used to get the first name, last name and account type of the user
$firstName = getFirstName($conn, $empNo); // Get the first name of the user
$lastName = getLastName($conn, $empNo); // Get the last name of the user
$accountType = getAccountType($conn, $empNo); // Get the account type of the user

// Authenticate the user
$isAuthenticated = authenticate($conn);

if (!$isAuthenticated) {
    // If not authenticated, redirect to the login page
    header("Location: ../index.php?error=notloggedin");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /> <!-- This meta tag is used to make the page responsive and scale to the device width -->
    <title>Source Tech Portal</title> <!-- Title of the page -->
    <!-- plugins:css -->
    <link rel="stylesheet" href="../vendors/feather/feather.css" /> <!-- Feather icons for the navbar -->
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css" /> <!-- Themify icons for the navbar -->
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css" /> <!-- Base css for the page -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css" /> <!-- Data tables css -->
    <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css" /> <!-- Themify icons for the navbar -->
    <link rel="stylesheet" type="text/css" href="../js/select.dataTables.min.css" /> <!-- Data tables css -->

    <link rel="stylesheet" href="css/select2/select2.min.css"> <!-- Select2 css -->
    <link rel="stylesheet" href="css/select2/"> <!-- Select2 css -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> <!-- Select2 css -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> <!-- Select2 js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css"> <!-- Bootstrap icons css -->

    <!-- End plugin css for this page -->
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
    <link rel="shortcut icon" href="../images/favicon.ico" />


    <script>
        // Loader script to make the loader disappear after 1.5 seconds
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

</head>

<!-- CSS -->
<style>
    /* Loader Styling */
    * {
        margin: 0;
        padding: 0;
    }

    .loader {
        position: fixed;
        top: 0;
        left: 0;
        background: #ededee;
        height: 100%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
    }

    .disppear {
        animation: vanish 1.5s forwards;
    }

    @keyframes vanish {
        100% {
            opacity: 0;
            visibility: hidden;
        }
    }

    .form-group select {
        font-size: 16px;
        color: #555;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-group select:focus {
        outline: none;
        border-color: #66afe9;
        box-shadow: 0 0 5px rgba(102, 175, 233, 0.5);
    }
</style>

<body>
    <!-- Loader -->
    <div class="loader">
        <img src="../images/loader.gif" alt="" />
    </div>

    <div class="container-scroller">

        <!-- partial:includes/_navbar.php -->
        <?php include "../includes/_navbar.php"; ?>

        <div class="container-fluid page-body-wrapper">

            <!-- partial - Account Type Based Navbar -->
            <!-- This will use the sidebar partial based on the account type in the session variable of the user and include it on the dasboard.php page -->
            <?php
            if ($accountType == 'Employee') {
                include '../includes/_employeesidebar.php';
            } elseif ($accountType == 'Manager') {
                include '../includes/_managersidebar.php';
            } elseif ($accountType == 'Administrator') {
                include '../includes/_adminsidebar.php';
            }
            ?>

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">Create a project</h3> <!-- Page title -->
                                </div>
                                <div class="row"></div>
                                <div class="col-lg-12 grid-margin stretch-card">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="container-fluid">
                                                <form action="../includes/signup.inc.php" method="POST"> <!-- Form to create a project, and all inputs get passed into the signup.php file -->
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <form>

                                                                <div class="form-group">
                                                                    <label for="projectName">
                                                                        Project name: <span style="color: red;">*</span>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="projectName" name="projectName" maxlength="25" required /> <!-- Project name input, also set to required -->
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="projectDescription">
                                                                        Project Description:
                                                                    </label>
                                                                    <div style="position: relative;">
                                                                        <textarea class="form-control" id="projectDescription" name="projectDescription" maxlength="150" oninput="updateCounter(this)" style="padding-right: 30px;"></textarea> <!-- Project description input, uses the updateCounter function to count the characters -->
                                                                        <span id="counter" style="position: absolute; bottom: 0; right: 10px; font-size: smaller;"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label for="priorityLevel">Priority Level:</label>
                                                                            <select class="form-control" id="priorityLevel" name="priorityLevel" required>
                                                                                <option value="high">High</option>
                                                                                <option value="medium">Medium</option>
                                                                                <option value="low">Low</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label for="projectStatus">Project Status:</label>
                                                                            <select class="form-control" id="projectStatus" name="projectStatus" required>
                                                                                <option value="in-progress">In Progress</option>
                                                                                <option value="pending">Pending</option>
                                                                                <option value="completed">Completed</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>



                                                                <div class="form-group">
                                                                    <label for="projectTeamID">Team ID: <span style="color: red;">*</span></label>
                                                                    <input type="text" class="form-control" id="projectTeamID" name="projectTeamID" placeholder="Search for a team ID" list="teamList1" required>

                                                                    <datalist id="teamList1">
                                                                        <?php
                                                                        // This basically gets all the teamIDs from the database and displays them as options in the datalist, from there the user can select a teamID from the list
                                                                        // Query the teams table to get all teamIDs
                                                                        $sql = "SELECT teamID FROM Teams"; // SQL query to get all teamIDs
                                                                        $result = mysqli_query($conn, $sql); // Run the query

                                                                        // Loop through the query results and display them as options in the datalist
                                                                        while ($row = mysqli_fetch_assoc($result)) { // Loop through the query results
                                                                            echo "<option value='" . $row["teamID"] . "'>"; // Display the teamID as an option
                                                                        }
                                                                        ?>
                                                                    </datalist>

                                                                    <script>
                                                                        // Listen for changes on the datalist input
                                                                        // Get the selected option and set the hidden input value to the corresponding data-value attribute
                                                                        // If the user types in a teamID that is not in the list, then the hidden input will be set to an empty string

                                                                        document.getElementById("teamID").addEventListener("input", function() { // Listen for changes on the datalist input
                                                                            // Get the selected option and set the hidden input value to the corresponding data-value attribute
                                                                            var option = document.querySelector("#teamList1 option[value='" + this.value + "']"); // Get the selected option
                                                                            if (option) {
                                                                                document.getElementById("teamID").value = option.value;
                                                                            }
                                                                        });
                                                                    </script>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="projectLead">Project Lead: <span style="color: red;">*</span></label>
                                                                    <input type="text" class="form-control" id="projectLead" name="projectLead" placeholder="Search for a lead" list="projectLeadList" required>
                                                                    <input type="hidden" id="projectLeadID" name="projectLeadID" value="">

                                                                    <datalist id="projectLeadList">
                                                                        <?php
                                                                        // This basically gets all the users with accountType "Manager" from the database and displays them as options in the datalist, from there the user can select a user from the list
                                                                        // Query the users table to get all users with accountType "Manager"
                                                                        $sql = "SELECT UserID, CONCAT(firstName, ' ', lastName) AS fullName FROM Users WHERE accountType = 'Manager'"; // SQL query to get all users with accountType "Manager"
                                                                        $result = mysqli_query($conn, $sql);

                                                                        // Loop through the query results and display them as options in the datalist
                                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                                            echo "<option value='" . $row["fullName"] . "' data-value='" . $row["UserID"] . "'>";
                                                                        }
                                                                        ?>
                                                                    </datalist>

                                                                    <script>
                                                                        // This basically gets all the users with accountType "Manager" from the database and displays them as options in the datalist, from there the user can select a user from the list
                                                                        // Listen for changes on the datalist input
                                                                        document.getElementById("projectLead").addEventListener("input", function() {
                                                                            // Get the selected option and set the value of the input field and hidden input to the corresponding data-value attribute
                                                                            var option = document.querySelector("#projectLeadList option[value='" + this.value + "']"); // Get the selected option
                                                                            if (option) {
                                                                                var userID = option.getAttribute("data-value"); // Get the data-value attribute
                                                                                document.getElementById("projectLeadID").value = userID; // Set the value of the hidden input
                                                                                this.value = option.value;
                                                                            }
                                                                        });
                                                                    </script>
                                                                </div>

                                                                <script>

                                                                    // Function to count the characters in the project description input
                                                                    function updateCounter(field) {
                                                                        var maxLength = 150;
                                                                        var currentLength = field.value.length;
                                                                        var counter = document.getElementById("counter");
                                                                        counter.textContent = currentLength + "/" + maxLength;
                                                                    }
                                                                </script>

                                                                <!-- Error messagea -->
                                                                <?php
                                                                if (isset($_GET['error']) && $_GET['error'] === "emptyinput") {
                                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%; color: red;'>" . htmlspecialchars($message) . "</div>";
                                                                }

                                                                //Project Name already exists
                                                                else if (isset($_GET['error']) && $_GET['error'] === "projectnamealreadyexists") {
                                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%; color: red;'>" . htmlspecialchars($message) . "</div>";

                                                                    //Team ID already exists
                                                                } else if (isset($_GET['error']) && $_GET['error'] === "projectidalreadyexists") {
                                                                    $message = isset($_GET['message']) ? $_GET['message'] : "An error has occurred.";
                                                                    echo "<div class='title' style='text-align: center; padding: 2% 0% 2% 0%; color: red;'>" . htmlspecialchars($message) . "</div>";
                                                                }
                                                                ?>
                                                                <!-- End of error message -->

                                                                <button type="submit" name="createProject" class="form-control btn     btn-primary rounded submit px-3"> <!-- Submit button -->
                                                                    <b>Create Project</b>
                                                                </button>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- content-wrapper ends -->

                            <!-- partial:includes/_footer.php -->
                            <?php include("../includes/_footer.php"); ?>
                            <!-- partial -->
                        </div>
                        <!-- main-panel ends -->
                    </div>
                    <!-- page-body-wrapper ends -->
                </div>
                <!-- container-scroller -->

                <!-- plugins:js -->
                <script src="../vendors/js/vendor.bundle.base.js"></script>
                <!-- endinject -->
                <!-- Plugin js for this page -->
                <script src="../vendors/chart.js/Chart.min.js"></script>
                <script src="../vendors/datatables.net/jquery.dataTables.js"></script>
                <script src="../vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
                <script src="../js/dataTables.select.min.js"></script>

                <!-- End plugin js for this page -->
                <!-- inject:js -->
                <script src="../js/off-canvas.js"></script>
                <script src="../js/hoverable-collapse.js"></script>
                <script src="../js/template.js"></script>
                <script src="../js/settings.js"></script>
                <script src="../js/todolist.js"></script>
                <!-- endinject -->
                <!-- Custom js for this page-->
                <script src="../js/dashboard.js"></script>
                <script src="../js/Chart.roundedBarCharts.js"></script>
                <!-- End custom js for this page-->

                <script>
                    // Loader animation script
                    var loader = document.querySelector(".loader")

                    window.addEventListener("load", vanish);

                    function vanish() {
                        loader.classList.add("disppear");
                    }
                </script>

</body>

</html>