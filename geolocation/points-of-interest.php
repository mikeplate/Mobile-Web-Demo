<?php
$latpos = floatval($_GET['lat']);
$longpos = floatval($_GET['long']);
$within = floatval($_GET['within']);
$type = $_GET['type'];

function distance($lat1, $lon1, $lat2, $lon2) { 
    $theta = $lon1 - $lon2; 
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
    $dist = acos($dist); 
    $dist = rad2deg($dist); 
    $miles = $dist * 60 * 1.1515;
    return ($miles * 1.609344); 
}

$file = fopen('../_priv/SE.txt', 'r');
$points = Array();
while ($line = fgets($file)) {
    $data = split("\t", $line);   
    if ($data[7] == $type) {
        $lat = floatval($data[4]);
        $long = floatval($data[5]);
        $dist = distance($lat, $long, $latpos, $longpos);
        if ($dist <= $within) {
            array_push($points, Array('name' => $data[1], 'distance' => $dist, 'lat' => $lat, 'long' => $long));
        }
    }
}
fclose($file);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($points);
?>

