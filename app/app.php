<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Snippet.php";

    // //Epicodus
    $server = 'mysql:host=localhost;dbname=expander';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    // home mac
    // $server = 'mysql:host=localhost:8889;dbname=expander';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

  //HOME
    $app->get("/", function() use ($app) {
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
    });

    //CREATE SNIPPET, RETURNS HOME
    $app->post("/new_snippet", function() use ($app) {
        $shortcut = ($_POST['shortcut']);
        $text = base64_encode($_POST['text']);
        $new_snippet = new Snippet($shortcut, $text);
        $new_snippet->save();
        return $app->redirect("/");
    });

    //DELETE ALL SNIPPETS, RETURNS HOME
    $app->post("/clear", function() use ($app) {
        Snippet::deleteAll();
        return $app->redirect("/");
    });

    //SNIPPET VIEW
    $app->get("/this_snippet/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        $snippet_text = $snippet->getText();
        $snippet_text = base64_decode($snippet_text);
        $snippet_vars = $snippet->countvars($snippet_text);
        $snippet_placeholders = $snippet->getPlaceHolders($snippet_text);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet, 'snippet_text' => $snippet_text, 'placeholders' => $snippet_placeholders, 'snippetvars' => $snippet_vars, 'finaltext' => ''));
    });

    //ADD VARIABLES TO SNIPPET
    $app->post("/add_variables/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        $snippet_text = $snippet->getText();
        $snippet_text = base64_decode($snippet_text);
        $snippet_placeholders = $snippet->getPlaceHolders($snippet_text);
        $snippet_vars = $snippet->countvars($snippet_text);

        $vars_array = array();
        for ($i = 1; $i <= count($snippet_vars); $i++) {
            $variable = $_POST['ł__' . $i . '__ł'];
            array_push($vars_array, $variable);
        }

        $final_text = $snippet->replacePlaceHolders($snippet_text, $vars_array);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet, 'snippet_text' => $snippet_text, 'placeholders' => $snippet_placeholders, 'snippetvars' => $snippet_vars, 'finaltext' => $final_text));
    });

    //DELETE SNIPPET, RETURNS HOME
    $app->delete("/delete_snippet/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        $snippet->delete();
        return $app->redirect("/");
    });

    //UPDATE VIEW
    $app->get("/update/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        $snippet_text = $snippet->getText();
        $snippet_text = base64_decode($snippet_text);
        return $app['twig']->render('update.html.twig', array('snippet' => $snippet, 'snippet_text' => $snippet_text));
    });

    //SHORTCUT UPDATE, RETURNS UPDATE VIEW
    $app->patch("/update_shortcut/{id}", function($id) use ($app) {
        $new_shortcut = $_POST['new_shortcut'];
        $snippet = Snippet::find($id);
        $snippet->updateShortcut($new_shortcut);
        return $app->redirect("/this_snippet/" . $snippet->getId());
    });

    //TEXT UPDATE, RETURNS UPDATE VIEW
    $app->patch("/update_text/{id}", function($id) use ($app) {
        $new_text = base64_encode($_POST['new_text']);
        $snippet = Snippet::find($id);
        $snippet->updateText($new_text);
        return $app->redirect("/this_snippet/" . $snippet->getId());
    });

    return $app;
?>
