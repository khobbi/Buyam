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

    //show error messages
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "dbConnection.php";
    //sql for total
    function getTotalStockPrice(): float{
        $total = 0.0;
        global $conn;

        $sql = $conn->real_escape_string("SELECT * FROM tb_items");
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            while($product = $result->fetch_assoc()){
                $total += $product["price"] * $product["qty_in_stock"];
            }
        }
        $result->close();
        return $total;
    }

    function getTotalPurchases(){
        $total = 0.0;
        global $conn;
    
        $sql = "SELECT price, quantity FROM tb_purchases, tb_items WHERE tb_purchases.item_id = tb_items._id";
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            while($purchase = $result->fetch_assoc()){
                $total += $purchase["price"] * $purchase["quantity"];
            }
        }
        return $total;
    }
    
    function stockProductsNum(){
        global $conn;

        $sql = "SELECT * FROM tb_items";
        $result = $conn->query($sql);
     
        return $result->num_rows;
    }

    function getNumUsers(){
        global $conn;
        $sql = "SELECT * FROM tb_users";
        $result = $conn->query($sql);
        return $result->num_rows;
    }

    $totalInvestment = getTotalStockPrice();
    $totalPurchases = getTotalPurchases();
    $totalProfit = $totalPurchases - $totalInvestment;
    $totalProdInStock = stockProductsNum();
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
            <div class="h2 text-end">Welcome, Admin!</div>
            <div class="h1 text-start">Site Overview</div>
            <div class="hr shadow bg-secondary " style="height: 2px;"></div>
        </div>
        
        <!-- site overview in grid -->
        <div class="mt-2 container-fluid">
            <!-- finance -->
           <div class="row">
                <div id="badge" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Total Investment</h5>
                    <h2>GhC <?php echo number_format($totalInvestment, 2);?></h2>
                </div>
                <div id="badge" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Total Purchases</h5>
                    <h2>GhC <?php echo number_format($totalPurchases, 2);?></h2>
                </div>
                <div id="badge" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Total Profits</h5>
                    <h2>GhC <?php echo number_format($totalProfit, 2);?></h2>
                </div>
           </div> 
           <!-- products -->
           <div class="row">
                <div id="badge1" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Products in Stock</h5>
                    <h2><?php echo $totalProdInStock;?></h2>
                </div>
                <div id="badge1" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Total Stock Price</h5>
                    <h2>GhC <?php echo number_format(getTotalStockPrice(), 2);?></h2>
                </div>
           </div> 
           <!-- users -->
           <div class="row">
                <div id="badge2" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Registered Users</h5>
                    <h2><?php echo getNumUsers();?></h2>
                </div>
                <div id="badge2" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Total Online Users</h5>
                    <h2>{num} <?php ?></h2>
                </div>
                <!-- <div id="badge2" class="col m-2 p-3 rounded shadow-sm my-badge">
                    <h5>Total Profits</h5>
                    <h2>GhC <?php ?></h2>
                </div> -->
           </div> 
           <div class=" shadow bg-secondary " style="height: 2px;"></div>
        </div>

        <!-- Site management -->
        <div class="mt-3 container-fluid">
            <div class="row">
                <button id="btn-badge" class="col m-2 p-3 rounded shadow-sm my-badge" onclick="document.location.href='userman.php'">
                    <span ><img src="images/icons/user.svg" alt="user icon" width="50px" style="opacity: 0.6;"></span>
                    <span >
                        <h5>User Management</h5>
                    </span>
                </button>
                <button id="btn-badge" class="col m-2 p-3 rounded shadow-sm my-badge" onclick="document.location.href='prodman.php'">
                    <span ><img src="images/icons/product1.svg" alt="user icon" width="50px" style="opacity: 0.6;"></span>
                    <span >
                        <h5>Product Management</h5>
                    </span>
                </button>
                <button id="btn-badge" class="col m-2 p-3 rounded shadow-sm my-badge" onclick="document.location.href='catman.php'">
                    <span ><img src="images/icons/category.svg" alt="user icon" width="50px" style="opacity: 0.6;"></span>
                    <span >
                        <h5>Category Management</h5>
                    </span>
                </button>
                <button id="btn-badge" class="col m-2 p-3 rounded shadow-sm my-badge" onclick="document.location.href='brandman.php'">
                    <span ><img src="images/icons/branding.svg" alt="user icon" width="50px" style="opacity: 0.6;"></span>
                    <span >
                        <h5>Brand Management</h5>
                    </span>
                </button>
            </div>
            <div class="hr shadow bg-secondary " style="height: 2px;"></div>
        </div>
    </body>
</html>