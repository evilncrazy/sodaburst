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
      return count($unpacked) ? $unpacked : array_values($self); // returns all fields if none was specified
   }
   
   public function fields() {
      return array_keys((array)$this);
   }
   
   public function match($pattern) {
      $pattern = is_array($pattern) ? $pattern : func_get_args();
      $self = $this->unpack();
      for($i = 0; $i < min(count($self), count($pattern)); $i++) {
         if($pattern[$i] !== null && $self[$i] !== $pattern[$i]) return false;
      }
      return true;
   }
   
   public function __toString() {
      return '[' . join(', ', $this->unpack()) . ']';
   }
}

// factory function for creating named tuples
function soda($fields = array()) {
   return new SodaTuple($fields);
}