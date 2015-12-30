<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    //Linking class for testing
    require_once "src/Store.php";

    //Setting server up to apache and mysql passwords.
    $server = 'mysql:host=localhost;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StoreTest extends PHPUnit_Framework_TestCase {

        //Clears data for next test after each test:
        protected function tearDown() {
            Store::deleteAll();
        }

        //Test getters:
        function test_getStoreName() {

            //Arrange
            $store_name = "Portland Running Company";
            $id = null;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            //Act
            $result = $test_store->getStoreName();

            //Assert
            $this->assertEquals($result, $store_name);
        }

        function testSetStoreName()
        {
            //Arrange
            $store_name = "Portland Running Company";
            $id = null;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            //Act
            $test_store->setStoreName("New Balance");
            $result = $test_store->getStoreName();

            //Assert
            $this->assertEquals("New Balance", $result);
        }

        function test_getId() {

            //Arrange
            $store_name = "Portland Running Company";
            $id = null;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            //Act
            $result = $test_store->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));
        }

        //Test save:
        function test_save() {
            //Arrange
            $store_name = "Portland Running Company";
            $id = null;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            //Act
            $result = Store::getAll();

            //Assert
            $this->assertEquals([$test_store], $result);
        }

        function testUpdate () {
            //Arrange
            $store_name = "Portland Running Company";
            $id = 1;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            $new_store_name = "New Balance";

            //Act
            $test_store->update($new_store_name);

            //Assert
            $this->assertEquals($new_store_name, $test_store->getStoreName());
        }

        function testDeleteStore()
        {
            //Arrange
            $store_name = "Portland Running Company";
            $id = 1;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            $store_name2 = "New Balance";
            $id2 = 2;
            $test_store2 = new Store($store_name, $id2);
            $test_store2->save();

            //Act
            $test_store->deleteOne();

            //Assert
            $this->assertEquals([$test_store2], Store::getAll());
        }

        //Test getAll:
        function test_getAll() {
            //Arrange
            $store_name = "Portland Running Company";
            $id = null;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            $store_name2 = "New Balance";
            $test_store2 = new Store($store_name2, $id);
            $test_store2->save();

            //Act
            $result = Store::getAll();

            //Assert
            $this->assertEquals([$test_store, $test_store2], $result);
        }

        //Test find:
        function test_find() {
            //Arrange
            $store_name = "Portland Running Company";
            $id = null;
            $test_store = new Store($store_name, $id);
            $test_store->save();

            $store_name2 = "New Balance";
            $test_store2 = new Store($store_name2, $id);
            $test_store2->save();

            //Act
            $id = $test_store->getId();
            $result = Store::find($id);

            //Assert
            $this->assertEquals($test_store, $result);
        }


    } //End Class
?>
