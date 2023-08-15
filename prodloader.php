<?php 
    require_once "dbConnection.php";

    $sql = "SELECT * FROM tb_items";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        //put 4 items in each row (result->num_rows = num of items)
        $rowsNeeded = $result->num_rows < 2 ? 1 + $result->num_rows / 4 : round($result->num_rows / 4);
        for($row = 0; $row < $rowsNeeded; $row++){
            echo "<div class='row mx-1'>";
            
            while($product = $result->fetch_assoc()){
                echo "<div class='col-sm p-0 m-2 rounded bg-white'>". 
                    "<a href='#'>
                        <img class='mx-auto d-block img-fluid' src='images/products/".$product["IMAGE"]."'" ."alt='Product Image' style='height:250px;'>
                    </a>" .
                    "<div class='m-2'>".
                        "<h3>$ " . number_format($product["price"]) . "</h3>" .
                        "<p class='text-secondary'>Category</p>" .
                        "<button class='btn btn-outline-primary'>Add to Cart</button>" .
                    "</div>" .
                "</div>";
            }
            
            echo "</div>";
        }
        
    } else {
        echo "Site under Construction...kindly be back soon...<br>";
    }
?>