<?php
//AUTHOR: Daan Bouwman
//initialize the database connection
$db = new SQLite3('../Weather_station.db');
//create an empty array to store the data
$jsonArray = array();
//$stations = $db->query('select Weather_station.location, x.node_id, x.time, x.temperature, x.ambient_light, x.barometric_pressure from Readings x inner join Weather_station on x.node_id = Weather_station.node_id join (select p.node_id, max(time) as max_time from Readings p group by p.node_id) y on y.node_id = x.node_id and  y.max_time = x.time group by x.node_id, x.time');
//get a list of weather stations
$stations = $db->query('select  * from Weather_station');
while ($row = $stations->fetchArray(1)) {
       
    //request the last 10 entries from the weather stations from readinigs list
    //this is the prepare statement and doesn't directly send the request. and :id is an variable which has te be assigned
    $statement = $db->prepare("SELECT temperature, ambient_light, barometric_pressure, time FROM Readings where node_id = :id and time >= date('now', '-3 hour') order by time desc");
     //assign :id
    $statement->bindValue(':id', $row['node_id']);
    $statement->bindValue(':id', $row['node_id']);
    //execute the SQL query
    $station = $statement->execute();

    //create another array to store the readings and store it  in an array
    $data = array();
    while ($result = $station->fetchArray(1)) {
        $data[] =  $result;
    }
    //add all the readings to the specific weather stations
    $row[] = $data;
    //add the weather stations with all the data to the array
    $jsonArray[] = $row;
}
//returns the data to the user
echo json_encode($jsonArray);
