<?php
    class Snippet
    {
        private $id;
        private $shortcut;
        private $text;

        function __construct($shortcut, $text, $id = null)
        {
            $this->shortcut = $shortcut;
            $this->text = $text;
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
                $text = $snippet['text'];
                $new_snippet = new Snippet($shortcut, $text, $id);
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
            $GLOBALS['DB']->exec("INSERT INTO snippets (shortcut, text) VALUES ('{$this->getShortcut()}', '{$this->getText()}');");
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
            return array_unique($placeholder_array);
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

    }
?>
