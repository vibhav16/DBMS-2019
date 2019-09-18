<?php include("includes/a_config.php");?>
<?php include("includes/connectionSettings.php");?>
<!DOCTYPE html>
<html>
<head>
	<?php include("includes/head-tag-contents.php");?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>jQuery UI Datepicker - Default functionality</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="http://services.iperfect.net/js/IP_generalLib.js"></script>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

	<script>
	$( function() {
		$( "#datepicker" ).datepicker();
	} );
	</script>
</head>
<body>
<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>
<form method="post">
<div class="container" id="main-content">
	<h3>Top performing Teams</h3>
	<p>
		<input type="submit" name="top10teamsWins" value="Top 10 Teams by most wins" class="btn btn-info btn-rounded" onclick="top10teamsWins()" />	
	</p>
	<p>
		<input type="submit" name="top10teamsGoals" value="Top 10 Teams by most goals" class="btn btn-info btn-rounded" onclick="top10teamsWins()" />	
	</p>
	<p>
		<input type="submit" name="top10teamsHomeWins" value="Top 10 Teams by most home wins" class="btn btn-info btn-rounded" onclick="top10teamsWins()" />	
	</p>
	<p>
		<input type="submit" name="top10teamsAwayWins" value="Top 10 Teams by most away wins" class="btn btn-info btn-rounded" onclick="top10teamsAwayWins()" />	
	</p>
	<p>
		<input type="submit" name="top10teamsRatio" value="Top 10 Teams with the best goals/shots ratio" class="btn btn-info btn-rounded" onclick="top10teamsRatio()" />	
	</p>
	<h3>Top performing Players</h3>
	<p>
	<h4> Top 10 players (based on goals) from country: </h4>
		<?php
			$cmd = 'SELECT DISTINCT P.NATIONALITY FROM PLAYER P ORDER BY NATIONALITY';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlCountry" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlCountry">'; // Open your drop down box

			$data  = 'ANY COUNTRY';
			echo '<option value="'.$data.'">'.$data.'</option>';
			// Loop through the query results, outputing the options one by one
			while (oci_fetch($statement)) {
				$data  = oci_result($statement,'NATIONALITY');
				echo '<option value="'.$data.'">'.$data.'</option>';
			}
			
			echo '</select>';
			//
			// VERY important to close Oracle Database Connections and free statements!
			//
			//oci_free_statement($statement);
			//oci_close($connection);
		?>
		<input type="submit" name="top10PlayersbyCntry" value="Get Ranking" class="btn btn-info btn-rounded" onclick="top10PlayersbyCntry()" />	
	
	</p>
	<p>
	<h4> Top 10 players with most goals on venue: </h4>
		<?php
			$cmd = 'SELECT DISTINCT G.VENUE FROM GAME G ORDER BY VENUE';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlVenues" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlVenues">'; // Open your drop down box

			$data  = 'ANY VENUE';
			echo '<option value="'.$data.'">'.$data.'</option>';
			// Loop through the query results, outputing the options one by one
			while (oci_fetch($statement)) {
				$data  = oci_result($statement,'VENUE');
				echo '<option value="'.$data.'">'.$data.'</option>';
			}
			
			echo '</select>';
			//
			// VERY important to close Oracle Database Connections and free statements!
			//
			//oci_free_statement($statement);
			//oci_close($connection);
		?>
		<input type="submit" name="top10PlayersbyVenue" value="Get Ranking" class="btn btn-info btn-rounded" onclick="top10PlayersbyVenue()" />	
	
	</p>
	<p>
	<h4> Top 10 players with most goals in season: </h4>
		<?php
			$cmd = 'SELECT DISTINCT G.SEASON FROM GAME G';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlSeasons" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlSeasons">'; // Open your drop down box

			$data  = 'ANY SEASON';
			echo '<option value="'.$data.'">'.$data.'</option>';
			// Loop through the query results, outputing the options one by one
			while (oci_fetch($statement)) {
				$data  = oci_result($statement,'SEASON');
				echo '<option value="'.$data.'">'.$data.'</option>';
			}
			
			echo '</select>';
			//
			// VERY important to close Oracle Database Connections and free statements!
			//
			//oci_free_statement($statement);
			//oci_close($connection);
		?>
		<input type="submit" name="top10PlayersbySeason" value="Get Ranking" class="btn btn-info btn-rounded" onclick="top10PlayersbySeason()" />	
	</p>
	<input type="submit" name="clearList" value="Clear Ranking" class="btn btn-info btn-rounded" onclick="window.location.reload()" />	
	<p>
		<?php
			// GET SQL QUERY AS PER USER REQUESTS
			// ---------------------------------------------------
			if(array_key_exists('top10teamsWins',$_POST)){
				$cmd = "SELECT A.TEAMNAME 
						FROM TEAM A, GAME_TEAMS_STATS B 
						WHERE A.TEAM_ID=B.TEAM_ID AND B.WON='TRUE'
						GROUP BY A.TEAMNAME
						ORDER BY COUNT(WON) DESC
						FETCH FIRST 10 ROWS ONLY";
				echo "TOP 10 Teams by WINS";
				getTeamRanking($connection, $cmd);
			}
			if(array_key_exists('top10teamsGoals',$_POST)){
				$cmd = "SELECT DISTINCT B.TEAMNAME
						FROM GAME_TEAMS_STATS A, TEAM B WHERE A.TEAM_ID=B.TEAM_ID
						GROUP BY B.TEAMNAME
						ORDER BY SUM(A.GOALS) DESC
						FETCH FIRST 10 ROWS ONLY";
				echo "TOP 10 Teams by Goals";
				getTeamRanking($connection, $cmd);
			}
			
			if(array_key_exists('top10teamsHomeWins',$_POST)){
				$cmd = "SELECT T.TEAMNAME 
						FROM TEAM T, GAME G
						WHERE T.TEAM_ID=G.HOME_TEAM_ID 
						AND OUTCOME LIKE 'home win%'
						GROUP BY T.TEAMNAME
						ORDER BY SUM(G.HOME_GOALS) DESC
						FETCH FIRST 10 ROWS ONLY";
				echo "TOP 10 Teams by Home WINS";
				getTeamRanking($connection, $cmd);
			}
			if(array_key_exists('top10teamsAwayWins',$_POST)){
				$cmd = "SELECT T.TEAMNAME 
						FROM TEAM T, GAME G
						WHERE T.TEAM_ID=G.AWAY_TEAM_ID 
						AND OUTCOME LIKE 'away win%'
						GROUP BY T.TEAMNAME
						ORDER BY SUM(G.AWAY_GOALS) DESC
						FETCH FIRST 10 ROWS ONLY";
				echo "TOP 10 Teams by Home goals";
				getTeamRanking($connection, $cmd);
			}
			if(array_key_exists('top10teamsRatio',$_POST)){
				$cmd = "SELECT DISTINCT A.TEAMNAME
						FROM TEAM A, GAME_TEAMS_STATS B 
						WHERE A.TEAM_ID=B.TEAM_ID
						ORDER BY (B.GOALS/B.SHOTS) DESC
						FETCH FIRST 10 ROWS ONLY";
				getTeamRanking($connection, $cmd);
			}
			if(array_key_exists('top10PlayersbyCntry',$_POST)){
				$c = $_POST['ddlCountry']; 
				$cmd = "SELECT A.FIRSTNAME, A.LASTNAME
						FROM PLAYER A, GAME_SKATER_STATS B 
						WHERE A.PLAYER_ID=B.PLAYER_ID 
						AND A.NATIONALITY='$c'
						GROUP BY A.FIRSTNAME, A.LASTNAME 
						ORDER BY COUNT(B.GOALS) DESC
						FETCH FIRST 10 ROWS ONLY";
				getPlayerRanking($connection, $cmd);
			}
			if(array_key_exists('top10PlayersbyVenue',$_POST)){
				$v = $_POST['ddlVenues']; 
				$cmd = "SELECT DISTINCT A.FIRSTNAME, A.LASTNAME
						FROM PLAYER A, GAME B, GAME_SKATER_STATS C 
						WHERE A.PLAYER_ID=C.PLAYER_ID 
						AND B.GAME_ID=C.GAME_ID 
						AND B.VENUE='$v'
						GROUP BY A.FIRSTNAME, A.LASTNAME 
						ORDER BY COUNT(C.GOALS) DESC
						FETCH FIRST 10 ROWS ONLY";
				getPlayerRanking($connection, $cmd);
			}
			
			if(array_key_exists('top10PlayersbySeason',$_POST)){
				$s = $_POST['ddlSeasons']; 
				$cmd = "SELECT DISTINCT A.FIRSTNAME, A.LASTNAME 
						FROM PLAYER A, GAME B, GAME_SKATER_STATS C 
						WHERE A.PLAYER_ID=C.PLAYER_ID 
						AND B.GAME_ID=C.GAME_ID 
						AND B.SEASON='$s'
						GROUP BY A.FIRSTNAME, A.LASTNAME 
						ORDER BY COUNT(C.GOALS) DESC
						FETCH FIRST 10 ROWS ONLY";
				getPlayerRanking($connection, $cmd);
			}
			// ---------------------------------------------------
			function getTeamRanking($connection, $cmd){
			// DISPLAY THE RETRIEVED TABLE
				$statement = oci_parse($connection, $cmd);
				oci_execute($statement);

				echo "<table border=\"1\">\n";
				echo "<tr>";
				echo "<th>Rank</th>";
				echo "<th>Team Name</th>";
				echo "</tr>\n";

				$ncols = oci_num_fields($statement) + 1;
				$cnt = 1;
				while (oci_fetch($statement)) {
					echo "<tr>";
					for ($i = 1; $i <= $ncols; $i++) {
						if ($i==1) $data  = $cnt;
						else if ($i==2) $data  = oci_result($statement, 'TEAMNAME');
						echo "<td>$data</td>";
						
					}
					echo "</tr>\n";
					$cnt += 1;
				}
				echo "</table>\n";
				
				// ---------------------------------------------------
				//
				// VERY important to close Oracle Database Connections and free statements!
				//
				//oci_free_statement($statement);
				//oci_close($connection);
			}
			function getPlayerRanking($connection, $cmd){
				// DISPLAY THE RETRIEVED TABLE
				$statement = oci_parse($connection, $cmd);
				oci_execute($statement);

				echo "<table border=\"1\">\n";
				echo "<tr>";
				echo "<th>Rank</th>";
				echo "<th>First Name</th>";
				echo "<th>Last Name</th>";
				echo "</tr>\n";

				$ncols = oci_num_fields($statement) + 1;
				$cnt = 1;
				while (oci_fetch($statement)) {
					echo "<tr>";
					for ($i = 1; $i <= $ncols; $i++) {
						if ($i==1) $data  = $cnt;
						else if ($i==2) $data  = oci_result($statement, 'FIRSTNAME');
						else if ($i==3) $data  = oci_result($statement, 'LASTNAME');
						echo "<td>$data</td>";
						
					}
					echo "</tr>\n";
					$cnt +=1;
				}
				echo "</table>\n";
				// ---------------------------------------------------
				//
				// VERY important to close Oracle Database Connections and free statements!
				//
				//oci_free_statement($statement);
				//oci_close($connection);
			}
		?>
	</p>
	<p>
		<?php
			//
			// VERY important to close Oracle Database Connections and free statements!
			//
			oci_free_statement($statement);
			oci_close($connection);
		?>
	</p>
</div>
</form>
<?php include("includes/footer.php");?>

</body>
</html>