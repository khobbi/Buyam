<?php
    //session
    session_start();
    if(!isset($_SESSION["username"])){
        header("Location: sign-in.php");
        exit;
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
        <?php include "main-navbar.php";?>
        <div class="h4 m-2"><u><?php echo "Welcome " . $_SESSION["username"];?></u></div>
        
        <?php include "prodloader.php";?>

        <?php include "footer.php";?>
    </body>
</html>