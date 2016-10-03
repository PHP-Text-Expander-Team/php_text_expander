<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Snippet.php";

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

    class snippet_test extends PHPUnit_Framework_TestCase

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
      // Testcode example
      //  function test_makeTitleCase_oneWord()
      //  {
      //      //Arrange
      //      $test_TitleCaseGenerator = new Template;
      //      $input = "beowulf";
       //
      //      //Act
      //      $result = $test_TitleCaseGenerator->testTemplate($input);
       //
      //      //Assert
      //      $this->assertEquals("Beowulf", $result);
      //  }
   }

 ?>
