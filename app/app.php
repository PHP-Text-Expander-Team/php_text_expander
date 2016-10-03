<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Snippet.php";

    // //Epicodus
    // $server = 'mysql:host=localhost;dbname=expander_test';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    // home mac
    $server = 'mysql:host=localhost:8889;dbname=expander';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

  //loads actual twig file
    $app->get("/", function() use ($app) {
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
    });

    // get text input and shortcut input
    $app->get("/snippets", function() use ($app) {
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
      });

    // post text input and shortcut input
    $app->post("new_snippet", function() use ($app) {
        $shortcut = $_POST['shortcut'];

    });



    return $app;
?>
