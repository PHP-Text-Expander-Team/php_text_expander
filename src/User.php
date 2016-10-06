<?php
    class User
    {
        private $id;
        private $user_name;
        private $password;
        private $email;

        function __construct($user_name, $password, $email, $id = null)
        {
            $this->user_name = $user_name;
            $this->password = $password;
            $this->email = $email;
            $this->id = $id;
        }
//--static functions--

        static function getAll()
        {
            $returned_users = $GLOBALS['DB']->query("SELECT * FROM users;");
            $users = array();
            foreach($returned_users as $user) {
                $user_name = $user['user_name'];
                $password = $user['password'];
                $email = $user['email'];
                $id = $user['id'];
                $new_user = new User($user_name, $password, $email, $id);
                array_push($users, $new_user);
            }
            return $users;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM users;");
        }

        static function find($search_name, $search_password)
        {
            $found_user = null;
            $users = User::getAll();

            foreach($users as $user) {
                $user_name = $user->getUserName();
                $password = $user->getPassword();
                if ($user_name == $search_name && $password == $search_password) {
                    $found_user = $user;
                }

            }
            return $found_user;
        }

//--regular functions--

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO users (user_name, password, email) VALUES ('{$this->getUserName()}', '{$this->getPassword()}', '{$this->getEmail()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM users WHERE id = {$this->id};");
        }

        function updateUserName($user_name)
        {
            $this->user_name = $user_name;
            $GLOBALS['DB']->exec("UPDATE users SET user_name = '{$this->user_name}' WHERE id = {$this->getId()};");
        }

        function updatePassword($password)
        {
            $this->password = $password;
            $GLOBALS['DB']->exec("UPDATE users SET password = '{$this->password}' WHERE id = {$this->getId()};");
        }

        function updateEmail($email)
        {
            $this->email = $email;
            $GLOBALS['DB']->exec("UPDATE users SET email = '{$this->email}' WHERE id = {$this->getId()};");
        }

//--getters and setters--
        function setUserName($user_name)
        {
            $this->user_name = $user_name;
        }

        function setPassword($password)
        {
            $this->password = $password;
        }

        function setEmail($email)
        {
            $this->email = $email;
        }

        function setId($id)
        {
            $this->id = $id;
        }

        function getUserName()
        {
            return $this->user_name;
        }

        function getPassword()
        {
            return $this->password;
        }

        function getEmail()
        {
            return $this->email;
        }

        function getId()
        {
            return $this->id;
        }

    }
?>
