<?php    
    //clean data
    function cleanData($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //insert new user
    function insertUser($user){
        global $conn;

        //get all users and check if exists
        $totalUsers = getAllUsers();
        foreach($totalUsers as $assocUser){
            if($assocUser["username"] == $user->userName || $assocUser["email"] == $user->email){
                return 2;
            }
        }

        //prepare n bind 
        $sql = "INSERT INTO tb_users(firstname, lastname, username, password, email, dob) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $user->firstName, $user->lastName, $user->userName, $user->password, $user->email, $user->dob);

        //execute
        if($stmt->execute()){
            //success
            return 1;
        } else {
            //failed
            return 0;
        }
    }

    //get all users
    function getAllUsers(){
        global $conn;

        $sql = "SELECT * FROM tb_users";
        $results = $conn->query($sql);
    
        $allUsers = $results->fetch_all(MYSQLI_ASSOC);
        return $allUsers;
    }
    
?>