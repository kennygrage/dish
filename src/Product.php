<?php

    require_once "User.php";

    class Product {

        private $product_name;
        private $product_sku;
        private $product_price;
        private $product_description;
        private $product_size;
        private $product_item_number;
        private $product_volume;
        private $id;

        function __construct($product_name, $product_sku, $product_price,
                              $product_description, $product_size, $product_item_number,
                              $product_volume, $id = null) {
            $this->product_name = $product_name;
            $this->product_sku = $product_sku;
            $this->product_price = $product_price;
            $this->product_description = $product_description;
            $this->product_size = $product_size;
            $this->product_item_number = $product_item_number;
            $this->product_volume = $product_volume;
            $this->id = $id;
        }

        function setProductName($new_product_name) {
            $this->product_name = $new_product_name;
        }

        function setProductSku($new_product_sku) {
            $this->product_sku = $new_product_sku;
        }

        function setProductPrice($new_product_price) {
            $this->product_price = $new_product_price;
        }

        function setProductDescription($new_product_description) {
            $this->product_description = $new_product_description;
        }

        function setProductSize($new_product_size) {
            $this->product_size = $new_product_size;
        }

        function setProductItemNumber($new_product_item_number) {
            $this->product_item_number = $new_product_item_number;
        }

        function setProductVolume($new_product_volume) {
            $this->product_volume = $new_product_volume;
        }


        function getProductName(){
            return $this->product_name;
        }

        function getProductSku(){
            return $this->product_sku;
        }

        function getProductPrice(){
            return $this->product_price;
        }

        function getProductDescription(){
            return $this->product_description;
        }

        function getProductSize(){
            return $this->product_size;
        }

        function getProductItemNumber(){
            return $this->product_item_number;
        }

        function getProductVolume(){
            return $this->product_volume;
        }

        function getId() {
            return $this->id;
        }

        //Save a product to products table:
        function save() {
            $statement = $GLOBALS['DB']->exec("INSERT INTO products (product_name,
                        product_sku, product_price, product_description, product_size,
                        product_item_number, product_volume)
                    VALUES ('{$this->getProductName()}', '{$this->getProductSku()}',
                        {$this->getProductPrice()}, '{$this->getProductDescription()}',
                        '{$this->getProductSize()}', {$this->getProductItemNumber()},
                        '{$this->getProductVolume()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }
/////START HERE///////
        //Get users that ordered a certain product
        function getUsers() {
            $query = $GLOBALS['DB']->query("SELECT * FROM orders_products_users WHERE products_id = {$this->getId()};");
            $users_ids = $query->fetchAll(PDO::FETCH_ASSOC);
            $users = Array();
            foreach($users_ids as $id) {
                $users_id = $id['users_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM users WHERE id = {$users_id};");
                $returned_user = $result->fetchAll(PDO::FETCH_ASSOC);
                $user_name = $returned_user[0]['user_name'];
                $id = $returned_user[0]['id'];
                $new_user = new User($user_name, $id);
                array_push($users, $new_user);
            }
            return $users;
        }

        function update($new_product_name) {
            $GLOBALS['DB']->exec("UPDATE products set product_name = '{$new_product_name}' WHERE id = {$this->getId()};");
            $this->setProductName($new_product_name);
        }

        function addUser($user) {
            $GLOBALS['DB']->exec("INSERT INTO users_products (products_id, users_id) VALUES ({$this->getId()}, {$user->getId()});");
        }

        function deleteOne()
        {
            $GLOBALS['DB']->exec("DELETE FROM products WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM users_products WHERE products_id = {$this->getId()};");
        }

        //Clear all products from products table:
        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM products;");
        }

        //Retrieve all products from products table:
        static function getAll() {
            $returned_products = $GLOBALS['DB']->query("SELECT * FROM products;");
            $found_products = $returned_products->fetchAll(PDO::FETCH_ASSOC);
            $products = array();
            foreach ($found_products as $product) {
                $product_name = $product['product_name'];
                $id = $product['id'];
                $new_product = new Product($product_name, $id);
                array_push($products, $new_product);
            }
            return $products;
        }

        //Find products by id in products table:
        static function find($search_id) {
            $search_product = $GLOBALS['DB']->query("SELECT * FROM products WHERE id = {$search_id}");
            $found_product = $search_product->fetchAll(PDO::FETCH_ASSOC);
            $product_name = $found_product[0]['product_name'];
            $id = $found_product[0]['id'];
            $new_user = new Product($product_name, $id);
            return $new_user;
        }
    }


?>
