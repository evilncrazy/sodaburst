<?php
class SodaTuple {
   public function __construct($fields = array()) {
      foreach($fields as $field => $value) {
         $this->{$field} = $value;
      }
   }
   
   public function __call($name, $args) {
      if(count($args)) return $this->{$name} = $args[0]; // set method
      return $this->{$name}; // get method
   }
   
   public function unpack($fields = array()) {
      $unpacked = array();
      $self = (array)$this;
      // unpack in the order of fields given
      foreach((is_array($fields) ? $fields : func_get_args()) as $field) {
         if(isset($self[$field])) $unpacked[] = $self[$field];
      }
      return count($unpacked) ? $unpacked : $self; // returns all fields if none was specified
   }
   
   public function fields() {
      return array_keys((array)$this);
   }
   
   public function __toString() {
      return '[' . join(', ', $this->unpack()) . ']';
   }
}

// factory function for creating named tuples
function soda($fields = array()) {
   return new SodaTuple($fields);
}