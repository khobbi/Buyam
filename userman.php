<?php 
    session_start();
    if(!isset($_SESSION["username"])){
        header("Location: index.php");
        exit;
    } else {
        if(!$_SESSION["username"] == "admin"){
            header("Location: index.php");
            exit;
        }
    }

    //show error messages
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    //required files for CRUD
    require_once "dbConnection.php";
    require "models/modelUser.php";
    require "dbCRUD.php";

    //variables
    $message;
    $success;

    //process submission of new user
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["newUserSubmit"])){
        //get user input from form
        $firstName = cleanData($_POST["firstName"]);
        $lastName = cleanData($_POST["secondName"]);
        $userName = cleanData($_POST["userName"]);
        $dob = cleanData($_POST["dob"]);
        $email = cleanData($_POST["email"]);
        $password = $_POST["password"];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // To encrypt the password

        //create new user
        $newUser = new User($firstName, $lastName, $userName, $email, $dob, $hashedPassword);

        //insert into table
        if(insertUser($newUser) == 1){
            //success
            $success = true;
        } elseif(insertUser($newUser) == 2) {
            //already exists
            $success = false;
            $message = "User already added.";
        } else {
            //failed
            $message = "Error occured during registration....";
            $success = false;
        }
        exit;
    }

    // //prevent resubmission
    // if($_SERVER["REQUEST_METHOD"] !== "POST"){
    //     //show form

    // } else {
    //     echo "<p>User inserted successfully...</p>";
    // }

    //process deletion of user
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["deleteUserSubmit"])){
        $prod_id = isset($_POST["prod_id"]) ? $_POST["prod_id"] : null;
        if(!isset($prod_id)){
            header("Location: userman.php");
            exit;
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>BuyAm: Dashboard</title>
        <meta charset="utf-8" lang="en">
        <meta name="viewport" content="width:device-width; initial-scale:1">
        <link rel="stylesheet" href="../bs5/css/bootstrap.min.css">
        <link rel="stylesheet" href="style1.css">
        <script src="../bs5/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <!-- insert navbar -->
        <?php include "navbar-l.php";?>

        <div class="container-fluid">
            <div class="row mb-2 border border-warning rounded">
                <div class="col text-start mt-2">
                    <!-- back to main admin page -->
                    <a href="admin_dashboard.php"><img src="images/icons/back-arrow-svgrepo-com.svg" width="24" alt="Back arrow"></a>
                </div>
                <div class="col text-end">
                    <h2 class="h2 text-end">Welcome, Admin!</h2>
                </div>
            </div>

            <div class="clearfix">
                <div class="h1 float-start">User Management</div>
                <button id="btn-badge" class="btn float-end" data-bs-toggle="modal" data-bs-target="#newUserModal" onclick="popTrig();">New Customer</button>
            </div>
            <div class="hr shadow bg-secondary" style="height: 2px;"></div>
        </div>

        <!-- main table for data -->
        <div class="table-responsive-sm">
            <table class="table">
                <tr>
                    <th>Frist name</th>
                    <th>Last name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>D. o B.</th>
                    <th></th>
                </tr>
                <?php 
                    //get all users from dbCRUD.php
                    $results = getAllUsers();

                    //puting the buttons in a post form in order to submit and process based on the ID
                    if(count($results) > 0){
                        foreach($results as $user){
                            $email = $user["email"];
                            echo "<tr style='nth-child(even) {background-color: #eee9ca;}'>
                            <td>" .$user["firstname"] . "</td>
                            <td>" .$user["lastname"] . "</td>
                            <td>" .$user["username"] . "</td>
                            <td>" .$user["email"] . "</td>
                            <td>" .$user["dob"] . "</td>
                            <td>
                                <button class='btn btn-sm btn-outline-warning'><img src='images/icons/edit.svg' width='20'></button>
                                <form method='POST' action='" .$_SERVER['PHP_SELF']. "'>
                                    <input type='hidden' style='display: inline-block' name='prod_id' value='" .$user['_id']."'></button>
                                    <button type='submit' name='deleteUserSubmit' class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal'><img src='images/icons/delete.svg' width='20'></button>
                                </form>
                            </td>
                            </tr>";
                        }
                    }
                ?>
            </table>
        </div>

        <!-- New user Modal -->
        <div class="modal" id="newUserModal">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" class="needs-validation">
                        <div class="form-floating mb-3">
                            <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First name" required>
                            <div class="invalid-feedback">Required.</div>
                            <label class="form-label" for="firstName">First name</label>
                        </div >
                        <div class="form-floating mb-3">
                            <input type="text" name="secondName" id="secondName" placeholder="Second name" class="form-control" required>
                            <div class="invalid-feedback">Required.</div>
                            <label for="secondName" class="form-label">Second name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="userName" id="userName" placeholder="Username" class="form-control" required>
                            <div class="invalid-feedback">Required.</div>
                            <label for="userName" class="form-label">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                            <div class="invalid-feedback">Required.</div>
                            <label for="email" class="form-label">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" name="dob" id="dob" placeholder="Date of Birth" class="form-control">
                            <label for="dob" class="form-label">Date of Birth</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                            <div class="invalid-feedback">Required.</div>
                            <label for="password" class="form-label">Password</label>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <input type="submit" value="Add" name="newUserSubmit" class="btn btn-outline-warning" <?php if(isset($success) && $success == true) : echo "data-bs-dismiss='modal'"; endif;?>>
                        </div>
                    </form>
                    
                </div>

                <div>
                    <?php if(isset($message)) : echo $message; endif;?>
                </div>

                </div>
            </div>
        </div>

        <!-- Delete modal -->
        <div class="modal" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <!-- head -->
                    <div class="modal-header">
                        <h5 class="modal-title">UserID</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- body -->
                    <div class="modal-body">
                        <?php 
                            echo $prod_id;
                        ?>
                    </div>
                    <!-- footer -->
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>