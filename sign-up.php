<?php
    //check if form is submitted
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        //connect to database
        require_once "dbConnection.php";

        //clean data
        function cleanData($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //get user input from form
        $firstName = cleanData($_POST["firstName"]);
        $lastName = cleanData($_POST["lastName"]);
        $userName = cleanData($_POST["username"]);
        $dob = cleanData($_POST["dob"]);
        $email = cleanData($_POST["email"]);
        $password = $_POST["password"];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // To encrypt the password

        //prepare sql INSERT statement
        $sql = "INSERT INTO tb_users (firstname, lastname, username, password, dob, email) VALUES (?, ?, ?, ?, ?, ?)";

        //prepare and bind
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $userName, $hashedPassword, $dob, $email);

        //execute statement
        if($stmt->execute()){
            //success, go to login
            header("Location: sign-in.php");
            exit();
        } else {
            $error_message = "Error occured during registration. Please try again later.";
        }

        //closing
        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>BuyAm: Sign-Up</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../bs5/css/bootstrap.min.css">
        <link rel="stylesheet" href="style1.css">
        <script src="../bs5/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <?php include "navbar.php";?>
        <div id="sign-in-div" class="container rounded shadow mx-auto p-4 needs-validation" style=" width:400px; margin-top:20px; margin-bottom:20px;">
            <form method="POST" action="<?php echo $_SERVER["PHP-SELF"];?>">
                <div class="mb-3 mt-3 form-floating">
                    <input class="form-control" type="text" name="firstName" id="firstName" placeholder="First name" required>
                    <label class="form-label" for="firstName">First name</label>
                    <div class="invalid-feedback">Field required.</div>
                </div> 
                <div class="mb-3 mt-3 form-floating">
                    <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last name" required>
                    <label for="lastName" class="form-label">Last name</label>
                    <div class="invalid-feedback">Field required.</div>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                    <label for="username" class="form-label">Username</label>
                    <div class="invalid-feedback">Field required.</div>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    <label class="form-label" for="email">Email</label>
                    <div class="invalid-feedback">Field is required.</div>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input type="date" name="dob" id="dob" class="form-control" placeholder="mm/dd/yyy" required>
                    <label class="form-label" for="dob">Date of Birth</label>
                </div>
                <div class="mb-3 mt-3 form-floating">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <label for="password" class="form-label">Password</label>
                    <div class="invalid-feedback">Field required.</div>
                </div>
                
                <input class="btn btn-primary mb-3" type="submit" value="SIGN UP" name="submit"><br>
                <p>Registered Already?</p>
                <input class="btn btn-secondary" type="button" value="SIGN IN" onclick="document.location.href='sign-in.php'">
            </form>
        </div>  

        <?php 
            if(isset($error_message)) : echo "<div class='text-danger p-3 rounded shadow-sm mx-auto ' style='width:400px;'>" .$error_message . "</div>"; 
            endif;
        ?>
    </body>
</html>