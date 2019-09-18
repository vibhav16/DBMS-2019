<?php include("includes/connectionSettings.php");?>
<?php
//setting header to json
header('Content-Type: application/json');

$cmd = sprintf("SELECT T.TEAMNAME, COUNT(G.GOALS) AS TOTAL_GOALS 
		FROM TEAM T, GAME_TEAMS_STATS G 
		WHERE T.TEAM_ID = G.TEAM_ID
		GROUP BY TEAMNAME, T.TEAMNAME
		ORDER BY TEAMNAME
		FETCH FIRST 10 ROWS ONLY");

$statement = oci_parse($connection, $cmd);
oci_execute($statement);

$data = array();
while($row = oci_fetch_array($statement,OCI_ASSOC)){
    $data[]=$row;
}

oci_free_statement($statement);
oci_close($connection);

print json_encode($data);
?>