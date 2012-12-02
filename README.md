Sodaburst
=========

Create lightweight named tuples in PHP. Essentially, named tuples are wrappers of associative arrays, 
allowing them to be used as objects. Sodaburst provides an easy way to create simple, throwaway data structures.

```php
/* soda(...) creates a named tuple from a key-value array */
$canOfSoda = soda(array(
   'volume' => 300,
   'fizziness' => 1.0
));

/* couldn't resist drink it... set volume to 200 */
$canOfSoda->volume(200);
echo $canOfSoda->volume(), $canOfSoda->fizziness();
```

Features
========
* Create objects on the fly using associative arrays
* Unpack tuples into variables ```list($vol, $fizz) = $canOfSoda->unpack()```

Usage
=====
Simply include ```sodaburst.php``` in your PHP code. Call ```soda()``` on an assocative array to create a named tuple.