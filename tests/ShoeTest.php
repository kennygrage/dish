<?php

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

//Linking class for testing
require_once "src/Shoe.php";
require_once "src/Store.php";

//Setting server up to apache and mysql passwords.
$server = 'mysql:host=localhost;dbname=shoes_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class ShoeTest extends PHPUnit_Framework_TestCase {

    //Clears data for next test after each test
    protected function tearDown() {
        Shoe::deleteAll();
        Store::deleteAll();
    }

    //Test getters:
    function test_getShoeName() {

        //Arrange
        $shoe_name = "Nike";
        $id = 1;
        $test_shoe = new Shoe($shoe_name, $id);
        $test_shoe->save();

        //Act
        $result = $test_shoe->getShoeName();

        //Assert
        $this->assertEquals($result, $shoe_name);
    }

    function testSetShoeName() {
        //Arrange
        $shoe_name = "Nike";
        $id = 1;
        $test_shoe = new Shoe($shoe_name, $id);

        //Act
        $test_shoe->setShoeName("Adidas");
        $result = $test_shoe->getShoeName();

        //Assert
        $this->assertEquals("Adidas", $result);
    }

    function test_getId() {
        //Arrange
        $shoe_name = "Nike";
        $id = null;
        $test_shoe = new Shoe($shoe_name, $id);
        $test_shoe->save();

        //Act
        $result = $test_shoe->getId();

        //Assert
        $this->assertEquals(true, is_numeric($result));
    }

    //Test save:
    function test_save() {
        //Arrange
        $shoe_name = "Nike";
        $id = null;
        $test_shoe = new Shoe($shoe_name, $id);
        $test_shoe->save();

        //Act
        $result = Shoe::getAll();

        //Assert
        $this->assertEquals([$test_shoe], $result);
    }

    //Test getAll:
    function test_getAll() {
        //Arrange
        $shoe_name = "Nike";
        $id = null;
        $test_shoe = new Shoe($shoe_name, $id);
        $test_shoe->save();

        $shoe_name2 = "Adidas";
        $test_shoe2 = new Shoe($shoe_name2, $id);
        $test_shoe2->save();

        //Act
        $result = Shoe::getAll();

        //Assert
        $this->assertEquals([$test_shoe, $test_shoe2], $result);
    }

    //Test deleteAll:
    function test_deleteAll(){
        //Arrange
        $shoe_name = "Nike";
        $id = null;
        $test_shoe = new Shoe($shoe_name, $id);
        $test_shoe->save();

        $shoe_name2 = "Adidas";
        $test_shoe2 = new Shoe($shoe_name2, $id);
        $test_shoe2->save();

        //Act
        Shoe::deleteAll();
        $result = Shoe::getAll();

        //Assert
        $this->assertEquals([], $result);
    }

    //Test find:
    function test_find(){
        //Arrange
        $shoe_name = "Nike";
        $id = null;
        $test_shoe = new Shoe($shoe_name, $id);
        $test_shoe->save();

        $shoe_name2 = "Adidas";
        $test_shoe2 = new Shoe($shoe_name2, $id);
        $test_shoe2->save();

        //Act
        $id = $test_shoe->getId();
        $result = Shoe::find($id);

        //Assert
        $this->assertEquals($test_shoe, $result);
    }

    //Test add store to shoe
    function test_addStore(){
        //Arrange
        $store_name = "New Balance";
        $id = 1;
        $test_store = new Store($store_name, $id);
        $test_store->save();

        $shoe_name = "Nike";
        $id2 = 2;
        $test_shoe = new Shoe($shoe_name, $id2);
        $test_shoe->save();

        //Act
        $test_shoe->addStore($test_store);
        $result = $test_shoe->getStores();

        //Assert
        $this->assertEquals([$test_store], $result);
    }

    function testUpdate() {
         //Arrange
         $shoe_name = "Nike";
         $id = 1;
         $test_shoe = new Shoe($shoe_name, $id);
         $test_shoe->save();
         $new_shoe_name = "Adidas";

         //Act
         $test_shoe->update($new_shoe_name);

         //Assert
         $this->assertEquals($new_shoe_name, $test_shoe->getShoeName());
     }

     function testDeleteShoe() {
         //Arrange
         $shoe_name = "Nike";
         $id = 1;
         $test_shoe = new Shoe($shoe_name, $id);
         $test_shoe->save();

         $shoe_name2 = "Adidas";
         $id2 = 2;
         $test_shoe2 = new Shoe($shoe_name2, $id2);
         $test_shoe2->save();

         //Act
         $test_shoe->deleteOne();

         //Assert
         $this->assertEquals([$test_shoe2], Shoe::getAll());
     }

     function testAddStore() {
         //Arrange
         $store_name = "New Balance";
         $id = 1;
         $test_store = new Store($store_name, $id);
         $test_store->save();

         $shoe_name = "Nike";
         $id2 = 2;
         $test_shoe = new Shoe($shoe_name, $id2);
         $test_shoe->save();

         //Act
         $test_shoe->addStore($test_store);
         $result = $test_shoe->getStores();

         //Assert
         $this->assertEquals([$test_store], $result);
     }

     function testGetStore() {
         //Arrange
         $store_name = "New Balance";
         $id = 1;
         $test_store = new Store($store_name, $id);
         $test_store->save();

         $store_name2 = "Portland Running Company";
         $id2 = 2;
         $test_store2 = new Store($store_name2, $id2);
         $test_store2->save();

         $shoe_name = "Nike";
         $id3 = 3;
         $test_shoe = new Shoe($shoe_name, $id3);
         $test_shoe->save();

         //Act
         $test_shoe->addStore($test_store);
         $test_shoe->addStore($test_store2);

         //Assert
         $this->assertEquals($test_shoe->getStores(), [$test_store, $test_store2]);
     }

     function testDelete() {
         //Arrange
         $store_name = "New Balance";
         $id = 1;
         $test_store = new Store($store_name, $id);
         $test_store->save();

         $shoe_name = "Nike";
         $id2 = 2;
         $test_shoe = new Shoe($shoe_name, $id2);
         $test_shoe->save();

         //Act
         $test_shoe->addStore($test_store);
         $test_shoe->deleteOne();

         //Assert
         $this->assertEquals([], $test_store->getShoes());
     }
 //Finished all shoe tests

}// End class

?>
