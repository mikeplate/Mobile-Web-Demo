<?php
$latpos = floatval($_GET['lat']);
$longpos = floatval($_GET['long']);

function distance($lat1, $lon1, $lat2, $lon2) { 
    $theta = $lon1 - $lon2; 
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
    $dist = acos($dist); 
    $dist = rad2deg($dist); 
    $miles = $dist * 60 * 1.1515;
    return ($miles * 1.609344); 
}

$file = fopen('../_priv/cities1000.txt', 'r');
$neardistance = -1;
$nearname = '';
$nearpop = 0;
$vicinity = Array();
while ($line = fgets($file)) {
    $data = split("\t", $line);   
    $lat = floatval($data[4]);
    $long = floatval($data[5]);
    $dist = distance($lat, $long, $latpos, $longpos);
    if ($neardistance==-1 || $dist<$neardistance) {
        $neardistance = $dist;
        $nearname = $data[1];
        $nearpop = intval($data[14]);
    }
    if ($dist < 20) {
        array_push($vicinity, Array('name' => $data[1], 'distance' => $dist, 'population' => intval($data[14])));
    }
}
fclose($file);

$output = Array(
    'name' => $nearname,
    'distance' => $neardistance,
    'population' => $nearpop,
    'vicinity' => $vicinity
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($output);
?>

