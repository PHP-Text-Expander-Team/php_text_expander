<?php
    class Snippet
    {
        private $id;
        private $shortcut;
        private $text;
        private $user_id;

        function __construct($shortcut, $text, $user_id, $id = null)
        {
            $this->shortcut = $shortcut;
            $this->text = $text;
            $this->id = $id;
            $this->user_id = $user_id;
        }
//--static functions--

        static function getAll()
        {
            $returned_snippets = $GLOBALS['DB']->query("SELECT * FROM snippets;");
            $snippets = array();
            foreach($returned_snippets as $snippet) {
                $id = $snippet['id'];
                $shortcut = $snippet['shortcut'];
                $text = $snippet['text'];
                $user_id = $snippet['user_id'];
                $new_snippet = new Snippet($shortcut, $text, $user_id, $id);
                array_push($snippets, $new_snippet);
            }
            return $snippets;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM snippets;");
            $GLOBALS['DB']->exec("DELETE FROM variables;");
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
            $GLOBALS['DB']->exec("INSERT INTO snippets (shortcut, text, user_id) VALUES ('{$this->getShortcut()}', '{$this->getText()}' '{$this->getUserId()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM snippets WHERE id = {$this->id};");
            $GLOBALS['DB']->exec("DELETE FROM variables WHERE snippet_id = {$this->id};");
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
            $search_array = explode("įį", $text);
            $pattern = "/(ł__)(\d)(__ł)/";

            for ($i = 0; $i < count($search_array) ; $i++)
            {
                if (preg_match($pattern, $search_array[$i]))
                {
                    array_push($placeholder_array, $search_array[$i]);
                }
            }
            return $placeholder_array;
        }

        function replacePlaceHolders($text, $array)
        {
            $pattern = "/(ł__)(\d)(__ł)/";
            $text_array = explode("įį", $text);
            for ($i = 0; $i < count($text_array); $i++)
            {
                if (preg_match($pattern, $text_array[$i]))
                {
                    $location = (int) substr($text_array[$i], 4, 1) - 1;
                    $text_array[$i] = $array[$location];
                }
            $final_text = implode($text_array);
            }
            return $final_text;
        }

        function countvars($text)
        {
        //user has to put in variables in order. display buttons as needed

            $text_array = explode("įį", $text);
            $pattern = "/(ł__)(\d)(__ł)/";
            $final_array = array();
            for ($i = 0; $i < count($text_array); $i++)
            {
                if (preg_match($pattern, $text_array[$i]))
                {
                    array_push($final_array, $text_array[$i]);
                }
            }
            return array_unique($final_array);
        }

//--getters and setters--
        function setShortcut($shortcut)
        {
            $this->shortcut = $shortcut;
        }

        function setText($text)
        {
            $this->text = $text;
        }

        function setUserId($user_id)
        {
            $this->user_id = $user_id;
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

        function getUserId()
        {
            return $this->user_id;
        }
    }
?>
