<?php namespace Ont\Telekinesis;

class Invoker {
    /*
     * Returns result requested via DSL tree represented in $json.
     * This DSL tree actually describes calls to Eloquent methods.
     */
    public function run($json)
    {
        //$this->tree = json_decode($json);   // parse..
        $this->tree = $json;
        $this->makeRoot();   // get root object instance

        return $this->apply( $this->tree['calls'], $this->root );
    }


    /*
     * Gets root class. All calls from $this->tree->calls will be
     * applyed on it.
     */
    private function makeRoot()
    {
        $class = $this->tree['class'];
        $this->root = new $class;
    }


    /*
     * Apply all "calls" from calls on object $obj
     */
    private function apply($calls, $obj)
    {
        $cur = $obj;
        foreach($calls as $call)
        {
            $name = $call['name'];
            $args = $this->prepareArgs($call['args']);

            $cur = call_user_func_array([$cur, $name], $args);   /// ... call one step in chain
        }
        return $cur;
    }

    private function prepareArgs($args)
    {
        $arr = [];
        foreach($args as $a)
        {
            if(isset($a['v']))
                $arr[] = $a['v'];

            // TODO: add support for classes (at this moment only closures are processed)
            if(isset($a['class'])) {
                $c = function($q) use ($a) {
                    $this->apply($a['calls'], $q);
                };

                $arr[] = $c;
            }
        }

        return $arr;
    }
}
?>

