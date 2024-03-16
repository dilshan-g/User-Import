<?php
  for ($i=1; $i <= 100; $i++)
  {
    // Since every number divisible by 15 divisible by 3 and 5.
    // Alternatively can use two checks like below
    // `($i % 3 === 0 && $i % 5 === 0)` to achieve the same output.
    if ($i % 15 === 0) {
      echo "foobar" . PHP_EOL;
    } elseif($i % 3 === 0) {
      echo "foo" . PHP_EOL;
    } elseif ($i % 5 === 0) {
      echo "bar" . PHP_EOL;
    } else {
      echo $i . PHP_EOL;
    }
  }
