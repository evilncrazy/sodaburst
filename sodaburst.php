<?php
class SodaTuple {
   public function __construct($fields = array()) {
      foreach($fields as $field => $value) {
         $this->{$field} = $value;
      }
   }
   
   public function __call($name, $args) {
      if(count($args)) return $this->{$name} = $args[0]; /* set method */
      return $this->{$name}; /* get method */
   }
   
   public function unpack() {
      return array_values((array)$this);
   }
   
   public function fields() {
      return array_keys((array)$this);
   }
   
   public function __toString() {
      return '[' . join(', ', $this->unpack()) . ']';
   }
}

/* Factory function for creating named tuples */
function soda($fields = array()) {
   return new SodaTuple($fields);
}