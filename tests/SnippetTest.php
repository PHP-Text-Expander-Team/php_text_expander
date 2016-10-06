<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Snippet.php";
    require_once "src/Variable.php";

    //Epicodus
    $server = 'mysql:host=localhost;dbname=expander_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    //home mac
    // $server = 'mysql:host=localhost:8889;dbname=expander_test';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    class Snippet_test extends PHPUnit_Framework_TestCase

    //run test in terminal: ./vendor/bin/phpunit tests

    //on Mac: run: export PATH=$PATH:./vendor/bin
    //then run phpunit tests

    {
        protected function teardown()
        {
            Snippet::deleteAll();
            Variable::deleteAll();
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
            $text = "Hi there ||||@!!@1@!!@|||| is your name really ||||@!!@1@!!@||||? Thats ||||@!!@2@!!@||||";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();
            $placeholder_array = ["Bob","crazy"];

            $result = $new_snippet->replacePlaceHolders($new_snippet->getText(), $placeholder_array);

            $this->assertEquals("Hi there Bob is your name really Bob? Thats crazy",$result);
        }

        function test_snippet_countvars()
        {
            $shortcut = ";letter";
            $text = "Hi there ||||@!!@1@!!@|||| is your name really ||||@!!@1@!!@||||? Thats ||||@!!@2@!!@||||";
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();

            $result = $new_snippet->countvars($new_snippet->getText());

            //need to change keys because array_unique deletes keys
            $this->assertEquals(["0"=>"@!!@1@!!@", "2"=>"@!!@2@!!@"], $result);
        }

        function test_snippet_long_save()
        {
            $shortcut = "long";
            $text = '<?php
                class Variable
                {
                    private $id;
                    private $snippet_id;
                    private $number;

                    function __construct($number, $snippet_id = null, $id = null)
                    {
                        $this->snippet_id = $snippet_id;
                        $this->number = $number;
                        $this->id = $id;
                    }
            //--static functions--

                    static function getAll()
                    {
                        $returned_variables = $GLOBALS["DB"]->query("SELECT * FROM variables;");
                        $variables = array();
                        foreach($returned_variables as $variable) {
                            $id = $variable["id"];
                            $snippet_id = $variable["snippet_id"];
                            $number = $variable["number"];
                            $new_variable = new Variable($number, $snippet_id, $id);
                            array_push($variables, $new_variable);
                        }
                        return $variables;
                    }

                    static function deleteAll()
                    {
                        $GLOBALS["DB"]->exec("DELETE FROM variables;");
                    }

                    static function find($search_id)
                    {
                        $found_variable = null;
                        $variables = Variable::getAll();

                        foreach($variables as $variable) {
                            $variable_id = $variable->getId();
                            if ($variable_id == $search_id) {
                                $found_variable = $variable;
                            }
                        }
                        return $found_variable;
                    }

            //--regular functions--

                    function save()
                    {
                        $GLOBALS["DB"]->exec("INSERT INTO variables (number) VALUES ({$this->getNumber()});");
                        $this->id = $GLOBALS["DB"]->lastInsertId();
                    }

                    function delete()
                    {
                        $GLOBALS["DB"]->exec("DELETE FROM variables WHERE id = {$this->id};");
                    }

            // GET / SET

                    function getId()
                    {
                        return $this->id;
                    }

                    function getNumber()
                    {
                        return $this->number;
                    }

                    function setNumber($number)
                    {
                        $this->number = $number;
                    }

                    function getSnippetId()
                    {
                        return $this->snippet_id;
                    }
                }
            ?>
';
            $new_snippet = new Snippet ($shortcut, $text);
            $new_snippet->save();

            $result = Snippet::getAll();

            $this->assertEquals([$new_snippet],$result);
            //test passes, database doesn't have any trouble storing it manually
        }
   }

 ?>
