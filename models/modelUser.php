<?php
    class User{
        public $firstName;
        public $lastName;
        public $userName;
        public $email;
        public $dob;
        public $password;

        public function __construct(string $firstName = "", string $lastName= "", string $userName= "", string $email = "", string $dob = "", string $password = ""){
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->userName = $userName;
            $this->email = $email;
            $this->dob = $dob;
            $this->password = $password;
        }
    }
?>