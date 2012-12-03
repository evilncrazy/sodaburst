<?php
class SodaTuple implements Countable, ArrayAccess {
   public function __construct(array $fields = array()) {
      foreach($fields as $field => $value) {
         // if field is a number, then we assume that this element is not associative,
         // so we treat value as the name of a field in the tuple with default value of null
         if(is_numeric($field)) $this->{$value} = null;
         else $this->{$field} = $value;
      }
   }
   
   public function __call($name, $args) {
      if(substr($name, 0, 4) == 'set_' || substr($name, 0, 4) == 'get_') $name = substr($name, 4);
      if(count($args)) return $this->offsetSet($name, $args[0]); // set method
      return $this->offsetGet($name); // get method
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
   
   public function assoc() {
      return (array)$this;
   }

   /* array access interface */
   public function offsetExists($offset) {
      if(is_numeric($offset)) return $offset >= 0 && $offset < count($this->fields());
      else return in_array($offset, $this->fields());
   }
    
   public function offsetUnset($offset) {
      $this->{$offset} = null;
   }
    
   public function offsetGet($offset) {
      if(!$this->offsetExists($offset)) return null;
      if(is_numeric($offset)) {
         $keys = $this->fields();
         return $this->{$keys[$offset]};
      } else return $this->{$offset};
   }

   public function offsetSet($offset, $value) {
      if(!is_null($offset) && $this->offsetExists($offset)) { /* immutable */
         if(is_numeric($offset)) {
            $keys = $this->fields();
            $this->{$keys[$offset]} = $value;
         } else $this->{$offset} = $value;
      } else throw new Exception((is_numeric($offset) ? 'Offset ' : 'Property ') . $offset . ' does not exist in record');
   }
   
   /* countable interface */
   public function count() {
      return count($this->fields());
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
   return new SodaTuple(is_array($fields) ? $fields : func_get_args());
}