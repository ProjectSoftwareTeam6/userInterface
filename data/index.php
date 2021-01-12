<?php
//AUTHOR: Daan Bouwman

//initialize database connection
$db = new SQLite3('../Weather_station.db');
//create empty array to store the data
$jsonArray = array();
//$stations = $db->query('select Weather_station.location, x.node_id, x.time, x.temperature, x.ambient_light, x.barometric_pressure from Readings x inner join Weather_station on x.node_id = Weather_station.node_id join (select p.node_id, max(time) as max_time from Readings p group by p.node_id) y on y.node_id = x.node_id and  y.max_time = x.time group by x.node_id, x.time');
//get all the different weather stations
$stations = $db->query('select  * from Weather_station');
while ($row = $stations->fetchArray(1)) {

    //request data from the weather stations from readinigs list
    //this is the prepare statement and doesn't directly send the request. and :id is an variable which has te be assigned
    $statement = $db->prepare('SELECT * FROM Readings WHERE node_id = :id order by time desc limit 1');
    //assing the :id variable to the node_id of the weather station
    $statement->bindValue(':id', $row['node_id']);
    //execute and store the data
    $result = $statement->execute();

    //push the data to the array
    $data = $result->fetchArray(1);
    array_push($data, array('location' => $row['location']));
    $jsonArray[] = $data;
}
//send the data to the user
echo json_encode($jsonArray);
