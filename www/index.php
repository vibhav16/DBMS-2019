<?php include("includes/a_config.php");?>
<?php include("includes/connectionSettings.php");?>
<!DOCTYPE html>
<html>
<head>
	<?php include("includes/head-tag-contents.php");?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="http://services.iperfect.net/js/IP_generalLib.js"></script>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>
<form method="post">
<div class="container" id="main-content">
	<h2>Welcome to the NHL information system!</h2>
	<p>
		This website consists of information about the National Hockey League.
		The National Hockey League is a professional ice hockey league in North America,
		currently comprising of 31 teams: 24 in the United States and 7 in Canada. The 
		NHL is considered to be the premier professional ice hockey league in the world, 
		and one of the major professional sports leagues in the United States and Canada 
		with a huge fan base.
	</p>
	<h3>THE BEST RIGHT NOW!</h3>
	<p>
		<input type="submit" name="bestTeambyWins" value="Best Team by most wins" class="btn btn-info btn-rounded" onclick="bestTeambyWins()" />	
	
		<input type="submit" name="bestTeambyGoals" value="Best Team by most goals" class="btn btn-info btn-rounded" onclick="bestTeambyGoals()" />	
	
		<input type="submit" name="bestSkaterGoals" value="Best Skater" class="btn btn-info btn-rounded" onclick="bestSkaterGoals()" />	
	
		<input type="submit" name="bestGoalieSaves" value="Best Goalies" class="btn btn-info btn-rounded" onclick="bestGoalieSaves()" />	
	</p>
	<p>
		<?php
			// GET SQL QUERY AS PER USER REQUESTS
			// ---------------------------------------------------
			if(array_key_exists('bestTeambyWins',$_POST)){
				$cmd = "SELECT A.TEAMNAME, A.SHORTNAME, A.ABBREVIATION, A.LINK,
						COUNT(B.WON) AS TOTAL_WINS, B.HEAD_COACH
						FROM TEAM A, GAME_TEAMS_STATS B 
						WHERE A.TEAM_ID=B.TEAM_ID AND B.WON='TRUE'
						GROUP BY A.TEAMNAME, A.SHORTNAME, A.ABBREVIATION, 
						A.LINK, B.HEAD_COACH
						ORDER BY COUNT(WON) DESC
						FETCH FIRST 1 ROWS ONLY";
				getBest($connection, $cmd, 1);
			}
			if(array_key_exists('bestTeambyGoals',$_POST)){
				$cmd = "SELECT A.TEAMNAME, A.SHORTNAME, A.ABBREVIATION, A.LINK,
						COUNT(B.WON) AS TOTAL_GOALS, B.HEAD_COACH
						FROM TEAM A, GAME_TEAMS_STATS B 
						WHERE A.TEAM_ID=B.TEAM_ID
						GROUP BY A.TEAMNAME, A.SHORTNAME, A.ABBREVIATION, 
                        A.LINK, B.HEAD_COACH
						ORDER BY COUNT(B.GOALS) DESC
						FETCH FIRST 1 ROWS ONLY";
				getBest($connection, $cmd, 2);
			}
			if(array_key_exists('bestSkaterGoals',$_POST)){
				$cmd = "SELECT A.FIRSTNAME, A.LASTNAME, A.NATIONALITY,
						A.PRIMARYPOSITION, A.LINK,
						COUNT(B.GOALS) AS TOTAL_GOALS
						FROM PLAYER A, GAME_SKATER_STATS B 
						WHERE A.PLAYER_ID=B.PLAYER_ID 
						GROUP BY A.FIRSTNAME, A.LASTNAME, A.NATIONALITY, A.PRIMARYPOSITION, A.LINK 
						ORDER BY TOTAL_GOALS DESC
						FETCH FIRST 1 ROWS ONLY";
				getBest($connection, $cmd, 3);
			}
			if(array_key_exists('bestGoalieSaves',$_POST)){
				$c = $_POST['ddlCountry']; 
				$cmd = "SELECT A.FIRSTNAME, A.LASTNAME, A.NATIONALITY,
						A.PRIMARYPOSITION, A.LINK,
						COUNT(B.SAVES) AS TOTAL_SAVES
						FROM PLAYER A, GAME_GOALIE_STATS B 
						WHERE A.PLAYER_ID=B.PLAYER_ID 
						GROUP BY A.FIRSTNAME, A.LASTNAME, A.NATIONALITY, A.PRIMARYPOSITION, A.LINK
						ORDER BY TOTAL_SAVES DESC
						FETCH FIRST 1 ROWS ONLY";
				getBest($connection, $cmd, 4);
			}
			// ---------------------------------------------------
			function getBest($connection, $cmd, $query){
			// DISPLAY THE RETRIEVED TABLE
				$statement = oci_parse($connection, $cmd);
				oci_execute($statement);

				echo "<table border=\"1\">\n";
				$ncols = oci_num_fields($statement);

				while (oci_fetch($statement)) {
					for ($i = 1; $i <= $ncols; $i++) {
						$column_name  = oci_field_name($statement, $i);
						
						if ($query==1) {
							if ($i==1) $data  = oci_result($statement,'TEAMNAME');
							else if ($i==2) $data  = oci_result($statement, 'SHORTNAME');
							else if ($i==3) $data  = oci_result($statement, 'ABBREVIATION');
							else if ($i==4) $data  = oci_result($statement, 'LINK');
							else if ($i==5) $data  = oci_result($statement, 'TOTAL_WINS');
							else if ($i==6) $data  = oci_result($statement, 'HEAD_COACH');
						} else if ($query==2) {
							if ($i==1) $data  = oci_result($statement,'TEAMNAME');
							else if ($i==2) $data  = oci_result($statement, 'SHORTNAME');
							else if ($i==3) $data  = oci_result($statement, 'ABBREVIATION');
							else if ($i==4) $data  = oci_result($statement, 'LINK');
							else if ($i==5) $data  = oci_result($statement, 'TOTAL_GOALS');
							else if ($i==6) $data  = oci_result($statement, 'HEAD_COACH');
						} else if ($query==3) {
							if ($i==1) $data  = oci_result($statement,'FIRSTNAME');
							else if ($i==2) $data  = oci_result($statement, 'LASTNAME');
							else if ($i==3) $data  = oci_result($statement, 'NATIONALITY');
							else if ($i==4) $data  = oci_result($statement, 'PRIMARYPOSITION');
							else if ($i==5) $data  = oci_result($statement, 'LINK');
							else if ($i==6) $data  = oci_result($statement, 'TOTAL_GOALS');
						} else if ($query==4) {
							if ($i==1) $data  = oci_result($statement,'FIRSTNAME');
							else if ($i==2) $data  = oci_result($statement, 'LASTNAME');
							else if ($i==3) $data  = oci_result($statement, 'NATIONALITY');
							else if ($i==4) $data  = oci_result($statement, 'PRIMARYPOSITION');
							else if ($i==5) $data  = oci_result($statement, 'LINK');
							else if ($i==6) $data  = oci_result($statement, 'TOTAL_SAVES');
						}
						echo "<tr>";
						echo "<td>$column_name</td>";
						echo "<td>$data</td>";
						echo "</tr>\n";
					}
				}
				echo "</table>\n";
				
				// ---------------------------------------------------
				//
				// VERY important to close Oracle Database Connections and free statements!
				//
				oci_free_statement($statement);
				oci_close($connection);
			}
		?>
	</p>
</div>
</form>
<?php include("includes/footer.php");?>

</body>
</html>
