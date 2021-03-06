<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Snippet.php";

    //Epicodus
    // $server = 'mysql:host=localhost;dbname=expander_test';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    //home mac
    $server = 'mysql:host=localhost:8889;dbname=expander_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class Snippet_test extends PHPUnit_Framework_TestCase

    //run test in terminal: ./vendor/bin/phpunit tests

    //on Mac: run: export PATH=$PATH:./vendor/bin
    //then run phpunit tests

    {
        protected function teardown()
        {
            Snippet::deleteAll();
        }

        function test_snippet_save()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();

            $result = Snippet::getAll();

            $this->assertEquals([$new_snippet],$result);
        }

        function test_snippet_getAll()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();
            $shortcut2 = ";name";
            $text2 = "Darth Vader";
            $new_snippet2 = new Snippet ($shortcut2, $text2);
            $new_snippet2->save();

            $result = Snippet::getAll();

            $this->assertEquals([$new_snippet, $new_snippet2], $result);
        }

        function test_snippet_deleteAll()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();
            $shortcut2 = ";name";
            $text2 = "Darth Vader";
            $new_snippet2 = new Snippet ($shortcut2, $text2);
            $new_snippet2->save();

            Snippet::deleteAll();
            $result = Snippet::getAll();

            $this->assertEquals([], $result);
        }

        function test_snippet_find()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();
            $shortcut2 = ";name";
            $text2 = "Darth Vader";
            $new_snippet2 = new Snippet ($shortcut2, $text2);
            $new_snippet2->save();

            $result = Snippet::find($new_snippet->getId());

            $this->assertEquals($new_snippet, $result);
        }

        function test_snippet_delete()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();
            $shortcut2 = ";name";
            $text2 = "Darth Vader";
            $new_snippet2 = new Snippet ($shortcut2, $text2);
            $new_snippet2->save();

            $new_snippet2->delete();
            $result = Snippet::getAll();

            $this->assertEquals([$new_snippet],$result);
        }

        function test_snippet_updateShortcut()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();

            $new_snippet->updateShortcut(";mail");
            $result = Snippet::getAll();

            $this->assertEquals([$new_snippet], $result);
        }

        function test_snippet_updateText()
        {
            $shortcut = ";em";
            $text = "me@email.com";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();

            $new_snippet->updateText("vader@jedissuck.com");
            $result = Snippet::getAll();

            $this->assertEquals([$new_snippet], $result);
        }

        function test_snippet_replacePlaceHolders()
        {
            $shortcut = ";letter";
            $text = "Hi there įįł__1__łįį is your name really įįł__1__łįį? Thats įįł__2__łįį";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();
            $placeholder_array = ["Bob","crazy"];

            $result = $new_snippet->replacePlaceHolders($new_snippet->getText(), $placeholder_array);

            $this->assertEquals("Hi there Bob is your name really Bob? Thats crazy",$result);
        }

        function test_snippet_countvars()
        {
            $shortcut = ";letter";
            $text = "Hi there įįł__1__łįį is your name really įįł__1__łįį? Thats įįł__2__łįį";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();

            $result = $new_snippet->countvars($new_snippet->getText());

            //need to change keys because array_unique deletes keys
            $this->assertEquals(["0"=>"ł__1__ł", "2"=>"ł__2__ł"], $result);
        }
   }

 ?>
