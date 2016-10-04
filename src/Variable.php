<?php
    class Variable
    {
        private $id;
        private $snippet_id;
        private $number;

        function __construct($snippet_id, $number, $id = null)
        {
            $this->snippet_id = $snippet_id;
            $this->number = $number;
            $this->id = $id;
        }
//--static functions--

        static function getAll()
        {
            $returned_variables = $GLOBALS['DB']->query("SELECT * FROM variables;");
            $variables = array();
            foreach($returned_variables as $variable) {
                $id = $variable['id'];
                $snippet_id = $variable['snippet_id'];
                $number = $variable['number'];
                $new_variable = new Variable($snippet_id, $number, $id);
                array_push($variables, $new_variable);
            }
            return $variables;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM variables;");
        }

//--regular functions--

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO variables (snippet_id, number) VALUES ({$this->getSnippetId()}, {$this->getNumber()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM variables WHERE id = {$this->id};");
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
