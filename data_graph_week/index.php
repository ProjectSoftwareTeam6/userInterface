<?php
     
    $db = new SQLite3('../Weather_station.db');
    $jsonArray = array();
    //$stations = $db->query('select Weather_station.location, x.node_id, x.time, x.temperature, x.ambient_light, x.barometric_pressure from Readings x inner join Weather_station on x.node_id = Weather_station.node_id join (select p.node_id, max(time) as max_time from Readings p group by p.node_id) y on y.node_id = x.node_id and  y.max_time = x.time group by x.node_id, x.time');
   $stations = $db-> query('select  * from Weather_station');
    while ($row = $stations->fetchArray(1)){

        $statement = $db->prepare("SELECT temperature, ambient_light, barometric_pressure, time FROM Readings where node_id = :id and time >= date('now', '-1 week') order by time desc");
        $statement->bindValue(':id', $row['node_id']);
        $station = $statement->execute();

        $data = array();
        while ($result = $station->fetchArray(1)){
            $data[] =  $result;
        }
        $row[] = $data;
        $jsonArray[] = $row;

    }
    echo json_encode($jsonArray);

?>  