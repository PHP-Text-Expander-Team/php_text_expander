<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Snippet.php";

    // //Epicodus
    $server = 'mysql:host=localhost;dbname=expander';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    // home mac
    // $server = 'mysql:host=localhost:8889;dbname=expander_test';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

  //get inputs
    $app->get("/", function() use ($app) {
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
    });

    // post text input and shortcut input
    $app->post("new_snippet", function() use ($app) {
        $shortcut = $_POST['shortcut'];
        $text = $_POST['text'];
        $new_snippet = new Snippet($shortcut, $text);
        $new_snippet->save();
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));

    // update snippet
    $app->patch("/update_snippet/{id}", function($id) use ($app) {
        $new_snippet = $_POST['new_snippet'];
        $snippet = Snippet::find($id);
        $snippet->update($new_snippet);
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
        });
    });

    // delete snippet
    $app->delete("/delete_snippet/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        $snippet->delete();
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
    });

    //delete all snippets
    $app->post("/clear", function() use ($app) {
        Snippet::deleteAll();
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
    });

    // show snippet
    $app->get("/this_snippet/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet));
    });
    return $app;
?>
