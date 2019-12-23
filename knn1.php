<?php
// Random cartesian coordinates (x, y) and labels
$data = array(
  array(1, 2, 'red'),   // 0 =>
  array(5, 3, 'blue'),  // 1 =>
  array(-1, 2, 'blue'), // 2 =>
  array(2, 5, 'red'),   // 3 =>
  array(3, 3, 'red'),   // 4 =>
  array(-4, 5, 'blue'), // 5 =>
  array(2, 2, 'blue'),  // 6 =>
  array(5, -2, 'red'),  // 7 =>
  array(-1, -2, 'blue'),// 8 =>
);

// Build distance matrix
$distances = $data;
array_walk($distances, 'euclideanDistance', $data);

// Example, target = datapoint 5, getting 3 nearest neighbors
//$neighbors = getNearestNeighbors($distances, 5, 3);
echo "<pre>";
print_r($distances);
echo "</pre>";

echo getLabel($data, getNearestNeighbors($distances, 5,3)) . "<br>";

/**
* Calculates eucilean distances for an array dataset
*
* @param array $sourceCoords In format array(x, y)
* @param array $sourceKey Associated array key
* @param array $data
* @return array Of distances to the rest of the data set
*/
function euclideanDistance(&$sourceCoords, $sourceKey, $data)
{
  $distances = array();
  list ($x1, $y1, $z1) = $sourceCoords;
  foreach ($data as $destinationKey => $destinationCoords) {
    // Same point, ignore
    if ($sourceKey == $destinationKey) {
      continue;
    }
    list ($x2, $y2, $z2) = $destinationCoords;
    $distances[$destinationKey] = sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
  }
  asort($distances);
  $sourceCoords = $distances;
}

/**
* Returns n-nearest neighbors
*
* @param array $distances Distances generated above ^
* @param mixed $key Array key of source location
* @param int $num Of neighbors to fetch
* @return array Of nearest neighbors
*/
function getNearestNeighbors($distances, $key, $num)
{
  return array_slice($distances[$key], 0, $num, true);
}

/**
* Gets result label from associated data
*
* @param array $data
* @param array $neighbors Result from getNearestNeighbors()
* @return string label
*/
function getLabel($data, $neighbors)
{
  $results = array();
  $neighbors = array_keys($neighbors);
  print_r($neighbors);
  foreach ($neighbors as $neighbor) {
    $results[] = $data[$neighbor][2];
  }
  $values = array_count_values($results);
  $values = array_flip($values);
  ksort($values);
  return array_pop($values);
}
?>
