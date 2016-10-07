<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Snippet.php";
    require_once __DIR__."/../src/User.php";

    // //Epicodus
    // $server = 'mysql:host=localhost;dbname=expander';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    // home mac
    $server = 'mysql:host=localhost:8889;dbname=expander';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    session_start();
    if (empty($_SESSION['current_user'])) {
        $_SESSION['current_user'] = null;
    }

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

  //HOME
    $app->get("/", function() use ($app) {
        return $app['twig']->render("home.html.twig", array('snippets' => Snippet::getAll(), 'user' => $_SESSION['user']));
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
        return $app['twig']->render('snippet.html.twig', array('user' => $_SESSION['user'], 'snippet' => $snippet, 'snippet_text' => $snippet_text, 'placeholders' => $snippet_placeholders, 'snippetvars' => $snippet_vars, 'finaltext' => ''));
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

    $app->patch("/update_text/{id}", function($id) use ($app) {
        $new_text = base64_encode($_POST['new_text']);
        $snippet = Snippet::find($id);
        $snippet->updateText($new_text);
        return $app->redirect("/this_snippet/" . $snippet->getId());
    });

    // SIGNUP PAGE
    $app->get("/sign_up", function() use ($app) {
        return $app['twig']->render('signup.html.twig', array('user' => $_SESSION['user']));
    });

    // POST SIGNUP INFO
    $app->post('/sign_up', function() use ($app) {
        $user_name = $_POST['user_name'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $id = null ;
        $new_user = new User($user_name, $password, $email, $id);
        $registered_user = $new_user->save();
        if ($registered_user == true) {
            $_SESSION['user'] = $new_user;
            return $app['twig']->render('login.html.twig', array('registered_user' => $new_user, 'user' => $_SESSION['user']));
        } else {
            return $app['twig']->render('signup.html.twig', array('user' => $_SESSION['user']));
        }
    });

    // LOGIN PAGE
    $app->get("/login", function() use ($app) {
        return $app['twig']->render('login.html.twig', array('user' => $_SESSION['user']));
    });

    $app->post('/user_login', function() use ($app) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $verify = User::verifyLogin($username, $password);
        if ($valid == false) {
            return $app['twig']->render('login.html.twig', array('user' => $_SESSION['user']));
        }
        return $app['twig']->render('home.html.twig', array('current_user' => $_SESSION['current_user']));
    });

    // LOGOUT
    $app->get('/logout', function() use ($app) {
        $_SESSION['user'] = null;
        return $app['twig']->render('login.html.twig', array('user' => $_SESSION['user']));
    });

    return $app;
?>
