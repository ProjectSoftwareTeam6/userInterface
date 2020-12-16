<?php
     
    $db = new SQLite3('../Weather_station.db');
    $jsonArray = array();
    //$stations = $db->query('select Weather_station.location, x.node_id, x.time, x.temperature, x.ambient_light, x.barometric_pressure from Readings x inner join Weather_station on x.node_id = Weather_station.node_id join (select p.node_id, max(time) as max_time from Readings p group by p.node_id) y on y.node_id = x.node_id and  y.max_time = x.time group by x.node_id, x.time');
   $stations = $db-> query('select  * from Weather_station');
    while ($row = $stations->fetchArray(1)){
        $jsonArray[] = $row;
    }
    echo json_encode($jsonArray);

?>  