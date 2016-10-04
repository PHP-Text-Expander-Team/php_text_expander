<?php
    class Snippet
    {
        private $id;
        private $shortcut;
        private $text;
        private $number_of_variables;

        function __construct($shortcut, $text, $number_of_variables = 0, $id = null)
        {
            $this->shortcut = $shortcut;
            $this->text = $text;
            $this->number_of_variables = $number_of_variables;
            $this->id = $id;
        }
//--static functions--

        static function getAll()
        {
            $returned_snippets = $GLOBALS['DB']->query("SELECT * FROM snippets;");
            $snippets = array();
            foreach($returned_snippets as $snippet) {
                $id = $snippet['id'];
                $shortcut = $snippet['shortcut'];
                $number_of_variables = $snippet['number_of_variables'];
                $text = $snippet['text'];
                $new_snippet = new Snippet($shortcut, $text, $number_of_variables, $id);
                array_push($snippets, $new_snippet);
            }
            return $snippets;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM snippets;");
        }

        static function find($search_id)
        {
            $found_snippet = null;
            $snippets = Snippet::getAll();

            foreach($snippets as $snippet) {
                $snippet_id = $snippet->getId();
                if ($snippet_id == $search_id) {
                    $found_snippet = $snippet;
                }
            }
            return $found_snippet;
        }

//--regular functions--

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO snippets (shortcut, text, number_of_variables) VALUES ('{$this->getShortcut()}', '{$this->getText()}', {$this->getNumberOfVariables()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM snippets WHERE id = {$this->id};");
        }

        function updateShortcut($shortcut)
        {
            $this->shortcut = $shortcut;
            $GLOBALS['DB']->exec("UPDATE snippets SET shortcut = '{$this->shortcut}' WHERE id = {$this->getId()};");
        }

        function updateText($text)
        {
            $this->text = $text;
            $GLOBALS['DB']->exec("UPDATE snippets SET text = '{$this->text}' WHERE id = {$this->getId()};");
        }

        function getPlaceHolders($text)
        {
            //make associative?
            $placeholder_array = array();
            $search_array = explode("||||", $text);
            $pattern = "/(@!!@)(\d|\d\d)(@!!@)/";

            for ($i = 0; $i < count($search_array) ; $i++)
            {
                if (preg_match($pattern, $search_array[$i]))
                {
                    array_push($placeholder_array, $search_array[$i]);
                }
            }
            $placeholder_array = array_unique($placeholder_array);
            $i = 0;
            foreach ($placeholder_array as $key => $placeholder) {
                $i ++;
                $placeholder = "||||@!!@" . $i . "@!!@||||";
            }
            return $placeholder_array;
        }

        function replacePlaceHolders($text, $array)
        {
            $text_array = explode(" ", $text);
            for ($i = 0; $i < count($text_array); $i++)
            {
                if ($text_array[$i] = "/(||||@!!@)(\d|\d\d)(@!!@||||)/")
                {
                    $location = 1;
                    $text_array[$i] = $array[$location];
                }
            $final_text = implode($text_array);
            }
            return $final_text;
        }

// "/(||||@!!@)(\d|\d\d)(@!!@||||)/"
//
// "Hi there @!!@1@!!@ is your name really @!!@1@!!@? Thats @!!@2@!!@"
        //break sentence into array, loop through array length. if i = regex search, push i to array
        //remember strstr() and substr_count as possibilities for getting # of variables

        //add variable property to text input that stores the number of variables in an array
        //
            // $placeholder_array = array();
            // preg_match("(@!!@)(\d|\d\d)(@!!@)")

            //remember strstr() and substr_count as possibilities for getting # of variables

//--getters and setters--
        function setShortcut($shortcut)
        {
            $this->shortcut = $shortcut;
        }

        function setText($text)
        {
            $this->text = $text;
        }

        function getShortcut()
        {
            return $this->shortcut;
        }

        function getText()
        {
            return $this->text;
        }

        function getId()
        {
            return $this->id;
        }

        function getNumberOfVariables()
        {
            return $this->number_of_variables;
        }

        function setNumberOfVariables($number_of_variables)
        {
            $this->number_of_variables = $number_of_variables;
        }

    }
?>
