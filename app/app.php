<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Snippet.php";
    require_once __DIR__."/../src/Variable.php";

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

  //get inputs
    $app->get("/", function() use ($app) {
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll()));
    });

    // post text input and shortcut input
    $app->post("/new_snippet", function() use ($app) {
        $shortcut = ($_POST['shortcut']);
        $text = base64_encode($_POST['text']);
        $new_snippet = new Snippet($shortcut, $text);
        $new_snippet->save();
        return $app->redirect("/");
    });

    $app->post("/create_variables/{id}", function($id) use ($app) {
        $variables = $_POST['number_of_variables'];

        $new_variable = new Variable($variables);
        $new_variable->save();

        $variable_id = $new_variable->getId();
        Variable::find($variable_id);

        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll(), 'variables' => $new_variable));
    });

    $app->get("/update/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        return $app['twig']->render('update.html.twig', array('snippet' => $snippet));
    });

    // update shortcut
    $app->patch("/update_shortcut/{id}", function($id) use ($app) {
        $new_shortcut = $_POST['new_shortcut'];
        $snippet = Snippet::find($id);
        $snippet->updateShortcut($new_shortcut);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet, 'placeholders' => $snippet_placeholders));
    });

    // update text
    $app->patch("/update_text/{id}", function($id) use ($app) {
        $new_text = $_POST['new_text'];
        $snippet = Snippet::find($id);
        $snippet->updateText($new_text);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet, 'placeholders' => $snippet_placeholders));
    });

    // allows to submit variables
    $app->post("/add_variables/{id}", function($id) use ($app) {
        $snippet = Snippet::find($id);
        $snippet_text = $snippet->getText();
        $snippet_placeholders = $snippet->getPlaceHolders($snippet_text);
        $snippet_vars = $snippet->countvars($snippet_text);

        $vars_array = array();
        for ($i = 1; $i <= count($snippet_vars); $i++) {
            $variable = $_POST['@!!@' . $i . '@!!@'];
            array_push($vars_array, $variable);
        }

        $final_text = $snippet->replacePlaceHolders($snippet_text, $vars_array);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet, 'placeholders' => $snippet_placeholders, 'snippetvars' => $snippet_vars, 'finaltext' => $final_text));
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
        $snippet_text = $snippet->getText();
        $snippet_text = base64_decode($snippet_text);
        $snippet_vars = $snippet->countvars($snippet_text);
        $snippet_placeholders = $snippet->getPlaceHolders($snippet_text);
        return $app['twig']->render('snippet.html.twig', array('snippet' => $snippet, 'snippet_text' => $snippet_text, 'placeholders' => $snippet_placeholders, 'snippetvars' => $snippet_vars, 'finaltext' => ''));
    });
    return $app;
?>
