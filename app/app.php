<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Snippet.php";

    //Epicodus
    $server = 'mysql:host=localhost;dbname=expander';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    // home mac
    // $server = 'mysql:host=localhost:8889;dbname=expander';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

  //loads actual twig file
    $app->get("/", function() use ($app) {
        return $app['twig']->render("home.html.twig", array( 'results' => getAll()));
    });

    // get text input and shortcut input

    $app->post("/snippets", function() use ($app) {
        $shortcut = $_POST[];
        $text = $_POST[];
        $new_snippet = new Snippet($shortcut, $text);
        $new_snippet->save();
        return $app['twig']->render("home.html.twig", array( 'results' => getAll()));
    });

    // post text input and shortcut input



    return $app;
?>
