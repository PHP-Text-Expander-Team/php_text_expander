<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Snippet.php";
    require_once "src/Variable.php";

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

    class Variable_test extends PHPUnit_Framework_TestCase

    //run test in terminal: ./vendor/bin/phpunit tests

    //on Mac: run: export PATH=$PATH:./vendor/bin
    //then run phpunit tests

    {
        protected function teardown()
        {
            Variable::deleteAll();
            Snippet::deleteAll();
        }

        function test_variable_save()
        {
            $number = 1;
            $new_variable = new Variable ($number);
            $new_variable->save();

            $result = Variable::getAll();

            $this->assertEquals([$new_variable], $result);
        }

        function test_variable_getAll()
        {
            $number = 1;
            $new_variable = new Variable ($number);
            $new_variable->save();

            $number2 = 1;
            $new_variable2 = new Variable ($number2);
            $new_variable2->save();

            $result = Variable::getAll();

            $this->assertEquals([$new_variable, $new_variable2], $result);
        }

        function test_variable_deleteAll()
        {
            $number = 1;
            $new_variable = new Variable ($number);
            $new_variable->save();

            $number2 = 1;
            $new_variable2 = new Variable ($number2);
            $new_variable2->save();

            Variable::deleteAll();
            $result = Variable::getAll();

            $this->assertEquals([], $result);
        }

        function test_snippet_delete()
        {
            $number = 1;
            $new_variable = new Variable ($number);
            $new_variable->save();

            $number2 = 1;
            $new_variable2 = new Variable ($number2);
            $new_variable2->save();

            $new_variable2->delete();
            $result = Variable::getAll();

            $this->assertEquals([$new_variable],$result);
        }

        function test_getNumber()
        {
            $number = 1;
            $new_variable = new Variable ($number);
            $new_variable->save();

            $result = $new_variable->getNumber();

            $this->assertEquals($number, $result);
        }
   }

 ?>
