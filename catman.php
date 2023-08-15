<?php
    //start session
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
        <?php include "navbar-l.php";?>
        <div class="container-fluid">
            <div class="row mb-2 border border-warning rounded">
                <div class="col text-start mt-2">
                    <a href="#"><img src="images/icons/back-arrow-svgrepo-com.svg" width="24" alt="Back arrow"></a>
                </div>
                <div class="col text-end">
                    <h2 class="h2 text-end">Welcome, Admin!</h2>
                </div>
            </div>
            <div class="clearfix">
                <div class="h1 text-start float-start">Category Management</div>
                <button id="btn-badge" class="btn float-end">New Category</button>
            </div>
            <div class="hr shadow bg-secondary" style="height: 2px;"></div>
        </div>

        <div class="table-responsive-sm">
            <table class="table">
                <tr>
                    <th>Category</th>
                    <th></th>
                </tr>
                <?php 
                    require_once "dbConnection.php";
                    $sql = $conn->real_escape_string("SELECT cat_name FROM tb_categories");
                    $result = $conn->query($sql);
                    if($result->num_rows > 0){
                        while($cat = $result->fetch_assoc()){
                            echo "<tr style='nth-child(even) {background-color: #eee9ca;}'>
                            <td>" .$cat["cat_name"] . "</td>
                            <td>
                                <button class='btn btn-sm btn-outline-warning'><img src='images/icons/edit.svg' width='20'></button>
                                <button class='btn btn-sm btn-outline-danger'><img src='images/icons/delete.svg' width='20'></button>
                            </td>
                            </tr>";
                        }
                    }
                ?>
            </table>
        </div>
    </body>
</html>