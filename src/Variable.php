<?php
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
            $returned_variables = $GLOBALS['DB']->query("SELECT * FROM variables;");
            $variables = array();
            foreach($returned_variables as $variable) {
                $id = $variable['id'];
                $snippet_id = $variable['snippet_id'];
                $number = $variable['number'];
                $new_variable = new Variable($number, $snippet_id, $id);
                array_push($variables, $new_variable);
            }
            return $variables;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM variables;");
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
            $GLOBALS['DB']->exec("INSERT INTO variables (number) VALUES ({$this->getNumber()});");
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
