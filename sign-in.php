<?php 
    //start the session to manage user login status
    session_start();

    //if user signed in, open dashboard
    if(isset($_SESSION["username"])){
        if($_SESSION["username"] == "admin"){
            header("Location: admin_dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit();
    }

    //check if the form is submittd
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        //db connection
        include_once "dbConnection.php";

        //get user input from the form
        $username = $_POST["username"];
        $password = $_POST["password"];

        //prepare sql statement
        $sql = "SELECT _id, username, password FROM tb_users WHERE username = ?";

        //prepare the statement and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);

        //execute the statement
        $stmt->execute();

        //get results from the statement
        $result = $stmt->get_result();

        //check if user exists
        if($result->num_rows === 1){
            //fetch the user record
            $user = $result->fetch_assoc();

            //verify the password
            if(password_verify($password, $user["password"])){
                //set the session variables
                $_SESSION["user_id"] = $user["_id"];
                $_SESSION["username"] = $user["username"];

                //redirect to appropriate dashboards
                if($user["username"] == "admin"){
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: customer_dashboard.php");
                }
                exit();
            } else {
                //incorrect password
                $error_message = "Invalid username or password.";
            }
        } else {
            //user not found
            $error_message = "Invalid username or password.";
        }

        //close statement and connection
        $stmt->close();
        $conn->close();
    }
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>BuyAm: Sign-In</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../bs5/css/bootstrap.min.css">
        <link rel="stylesheet" href="style1.css">
        <script src="../bs5/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <?php include "navbar.php";?>

        <div id="sign-in-div" class="container rounded mx-auto shadow p-4" style=" width: 400px; margin-top:20px; margin-bottom:20px; ">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP-SELF"])?>" class="needs-validation">
                <div class="form-floating mb-3 mt-3">
                    <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                    <div class="invalid-feedback">Field required.</div>
                    <label for="username" class="form-label" >Username</label>
                </div>
                <div class="form-floating mt-3 mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required> 
                    <div class="invalid-feedback">Field required.</div>
                    <label for="password" class="form-label">Password</label>
                </div>
                <a href="#" class="nav-link text-end text-secondary">Forgot Password?</a>
                <div class="form-check mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" name="remember" class="form-check-input">Remember Me
                    </label>
                </div>
                <div class="mb-3 clearfix">
                    <input type="submit" value="SIGN IN" name="submit" class="btn btn-primary float-start">
                    
                    <input class="btn btn-secondary float-end " type="button" value="SIGN UP" onclick="document.location.href='sign-up.php'">
                </div>                
            </form>
        </div>

        <?php 
            if(isset($error_message)) : echo "<div class='text-danger p-3 rounded shadow-sm mx-auto ' style='width:400px;'>" .$error_message . "</div>"; 
            endif;
        ?>
    </body>
</html>