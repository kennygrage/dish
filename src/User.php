<?php

class User {

    private $user_first_name;
    private $user_last_name;
    private $id;


    function __construct($user_first_name, $user_last_name, $id = null) {
        $this->user_first_name = $user_first_name;
        $this->user_last_name = $user_last_name;
        $this->id = $id;
    }

    function setUserFirstName($new_user_first_name){
        $this->user_first_name = $new_user_first_name;
    }

    function getUserFirstName(){
        return $this->user_first_name;
    }

    function setUserLastName($new_user_last_name){
        $this->user_last_name = $new_user_last_name;
    }

    function getUserLastName(){
        return $this->user_last_name;
    }

    function getId(){
        return $this->id;
    }

    //Add an order to the user
    function orders_products_users($orders, $products){
      foreach($products as $product) {
        $GLOBALS['DB']->exec("INSERT INTO orders_products_users (orders_id, products_id, users_id)
                    VALUES ({$orders->getId()}, {$product->getId()}, {$this->getId()});");
      }
    }

    //Get all orders for a user:
    function getOrders() {
        //join statement
        $found_orders = $GLOBALS['DB']->query("SELECT orders.* FROM
        users JOIN orders_products_users ON (users.id = orders_products_users.users_id)
                 JOIN orders ON (orders_products_users.orders_id = orders.id)
                 WHERE (users.id = {$this->getId()});");
         //convert output of the join statement into an array
         $found_orders = $found_orders->fetchAll(PDO::FETCH_ASSOC);
         $user_orders = array();
         foreach($found_orders as $found_order) {
             $id = $found_order['id'];
             $new_order = new Order($id);
             array_push($user_orders, $new_order);
         }
         return $user_orders;
    }

    //Save a user to users table:
    function save() {
        $statement = $GLOBALS['DB']->exec("INSERT INTO users (user_first_name, user_last_name)
                        VALUES ('{$this->getUserFirstName()}', '{$this->getUserLastName()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    //change user name
    function update($new_user_first_name, $new_user_first_name) {
        $GLOBALS['DB']->exec("UPDATE users SET user_first_name = '{$new_user_first_name}' WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("UPDATE users SET user_last_name = '{$new_user_last_name}' WHERE id = {$this->getId()}");
        $this->setUserFirstName($new_user_first_name);
        $this->setUserLastName($new_user_last_name);
    }

    //delete one user
    function deleteOne() {
        $GLOBALS['DB']->exec("DELETE FROM users WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM orders_product_users WHERE users_id = {$this->getId()};");
    }

    //Retrieve all users from users table:
    static function getAll(){
        $returned_users = $GLOBALS['DB']->query("SELECT * FROM users;");
        $users = array();
        foreach ($returned_users as $user) {
            $user_first_name = $user['user_first_name'];
            $user_last_name = $user['user_last_name'];
            $id = $user['id'];
            $new_user = new User ($user_first_name, $user_last_name, $id);
            array_push($users, $new_user);
        }
        return $users;
    }

    //Find users by id in users table:
    //Built finder with DB query string instead of foreach loop.
    static function find($search_id){
        $search_user = $GLOBALS['DB']->query("SELECT * FROM users WHERE id = {$search_id}");
        $found_user = $search_user->fetchAll(PDO::FETCH_ASSOC);
        $user_first_name = $found_user[0]['user_first_name'];
        $user_last_name = $found_user[0]['user_last_name'];
        $id = $found_user[0]['id'];
        $new_user = new User($user_first_name, $user_last_name, $id);
        return $new_user;
    }



}

?>
