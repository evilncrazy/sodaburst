<?php
class SodaTuple {
   public function __construct(array $fields = array()) {
      $fields = is_array($fields) ? $fields : func_get_args();
      foreach($fields as $field => $value) {
         // if field is a number, then we assume that this element is not associative,
         // so we treat value as the name of a field in the tuple with default value of null
         if(is_numeric($field)) $this->{$value} = null;
         else $this->{$field} = $value;
      }
   }
   
   public function __call($name, $args) {
      if(count($args)) {
         if(isset($this->{$name})) return $this->{$name} = $args[0]; // set method
         else throw new Exception('Property ' . $name . ' does not exist in record');
      }
      return $this->{$name}; // get method
   }
   
   public function unpack($fields = array()) {
      $unpacked = array();
      $self = (array)$this;
      $values = array_values($self);
      
      // unpack elements in the order of fields given
      foreach((is_array($fields) ? $fields : func_get_args()) as $field) {
         if(is_numeric($field)) $unpacked[] = $values[$field];
         else if(isset($self[$field])) $unpacked[] = $self[$field];
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
         // allow wildcard nulls
         if($pattern[$i] !== null && $self[$i] !== $pattern[$i]) return false;
      }
      return true;
   }
   
   public function copy($values = array()) {
      $values = is_array($values) ? $values : func_get_args();
      if(count($values))
         return new SodaTuple(array_combine($this->fields(), array_pad($values, count($this->fields()), null)));
      else return new SodaTuple((array)$this);
   }
   
   public function __toString() {
      return '[' . join(', ', $this->unpack()) . ']';
   }
}

// factory function for creating named tuples
function soda($fields = array()) {
   return new SodaTuple($fields);
}