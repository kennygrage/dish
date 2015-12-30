<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/User.php";
    require_once __DIR__."/../src/Product.php";

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=shoes';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    //root page
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('stores' => Store::getAll(), 'shoes' => Shoe::getAll()));
    });

    //shoes page
    $app->get("/shoes", function() use ($app) {
      return $app['twig']->render('shoes.html.twig', array('shoes' => Shoe::getAll(), 'stores' => Store::getAll()));
    });

    //stores page
    $app->get("/stores", function() use ($app) {
      return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //stores post page
    $app->post("/stores", function() use ($app) {
      $store = new Store($_POST['store_name']);
      $store->save();
      return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //specific store page
    $app->get("/stores/{id}", function($id) use ($app) {
      $store = Store::find($id);
      return $app['twig']->render('store.html.twig', array('store' => $store, 'shoes' => $store->getShoes(), 'all_shoes' => Shoe::getAll()));
    });

    //edit store
    $app->post("/stores/{id}/edit", function($id) use($app) {
        $store = Store::find($id);
        $store->update($_POST['store_name']);
        return $app['twig']->render('store.html.twig', array('store' => $store, 'shoes' => $store->getShoes(), 'all_shoes' => Shoe::getAll()));
    });

    //delete store
    $app->get("/stores/{id}/delete", function($id) use($app) {
        $store = Store::find($id);
        $store->deleteOne();
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //post shoes page
    $app->post("/shoes", function () use ($app) {
      $id = null;
      $shoe = new Shoe($_POST['shoe_name'], $id);
      $shoe->save();
      return $app['twig']->render('shoes.html.twig', array('shoes' => Shoe::getAll(), 'stores' => Store::getAll()));
    });

    //specific shoe page
    $app->get("/shoes/{id}", function ($id) use ($app) {
      $shoe = Shoe::find($id);
      return $app['twig']->render('shoe.html.twig', array('shoe' => $shoe, 'stores' => $shoe->getStores(), 'all_stores' => Store::getAll()));
    });

    //edit shoe page
    $app->post("/shoes/{id}/edit", function ($id) use ($app) {
        $shoe = Shoe::find($id);
        $shoe->update($_POST['shoe_name']);
        return $app['twig']->render('shoe.html.twig', array('shoe' => $shoe, 'stores' => $shoe->getStores(), 'all_stores' => Store::getAll()));
    });

    //delete shoe page
    $app->get("/shoes/{id}/delete", function($id) use($app) {
        $shoe = Shoe::find($id);
        $shoe->deleteOne();
        return $app['twig']->render('shoes.html.twig', array('shoes' => Shoe::getAll()));
    });

    //delete all shoes page
    $app->post("/delete_shoes", function() use ($app) {
      Shoe::deleteAll();
      return $app['twig']->render('index.html.twig');
    });

    //delete all stores page
    $app->post("/delete_stores", function() use ($app) {
      Store::deleteAll();
      return $app['twig']->render('index.html.twig');
    });

    //add shoes page
    $app->post("/add_shoes", function () use ($app){
     $store = Store::find($_POST['store_id']);
     $shoe = Shoe::find($_POST['shoe_id']);
     $store->addShoe($shoe);
     return $app['twig']->render('store.html.twig', array('store'=>$store, 'stores' => Store::getAll(), 'shoes' => $store->getShoes(), 'all_shoes' => Shoe::getAll()));
    });

    //add stores page
    $app->post("/add_stores", function () use ($app){
     $store = Store::find($_POST['store_id']);
     $shoe = Shoe::find($_POST['shoe_id']);
     $shoe->addStore($store);
     return $app['twig']->render('shoe.html.twig', array('shoe' => $shoe, 'shoes' => Shoe::getAll(), 'stores' => $shoe->getStores(), 'all_stores' => Store::getAll()));
    });

    return $app;
?>
