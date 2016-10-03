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

        }

        static function deleteAll()
        {

        }

        static function find()
        {

        }

//--regular functions--

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO snippets (shortcut, text) VALUES ({$this->getShortcut()}, {$this->getText()});");
        }

        function delete()
        {

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
