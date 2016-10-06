<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Snippet.php";
    require_once "src/Variable.php";
    require_once "src/User.php";

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

    class User_test extends PHPUnit_Framework_TestCase

    //run test in terminal: ./vendor/bin/phpunit tests

    //on Mac: run: export PATH=$PATH:./vendor/bin
    //then run phpunit tests

    {
        protected function teardown()
        {
            Snippet::deleteAll();
            Variable::deleteAll();
            User::deleteAll();
        }

        function test_user_save()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();

            $result = User::getAll();

            $this->assertEquals([$new_user],$result);
        }

        function test_user_getAll()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            $result = User::getAll();

            $this->assertEquals([$new_user, $new_user2], $result);
        }

        function test_user_deleteAll()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            User::deleteAll();
            $result = User::getAll();

            $this->assertEquals([], $result);
        }

        function test_user_find()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            $result = User::find($new_user->getId());

            $this->assertEquals($new_user, $result);
        }

        function test_user_delete()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            $new_user2->delete();
            $result = User::getAll();

            $this->assertEquals([$new_user],$result);
        }

        function test_updateUserName()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            $new_user->updateUserName("InsaneClown");
            $result = User::getAll();

            $this->assertEquals([$new_user], $result);
        }

        function test_updateUserPassword()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            $new_user->updateUserPassword("mcmuffin");
            $result = User::getAll();

            $this->assertEquals([$new_user], $result);
        }

        function test_updateUserEmail()
        {
            $user_name = "XxX420BlazeItPHPBoyzXxX";
            $password = "macmuffin";
            $email = "james.mcneil@gmail.com"
            $new_user = new User ($user_name, $password, $email);
            $new_user->save();
            $user_name2 = "phaze_XxCutThroatNoScopexX";
            $password2 = "starbucks";
            $email2 = "italianstalion6969@gmail.com"
            $new_user2 = new User ($user_name, $password, $email);
            $new_user2->save();

            $new_user->updateUserEmail("jamie.mcneil@gmail.com");
            $result = User::getAll();

            $this->assertEquals([$new_user], $result);
        }
   }

 ?>
