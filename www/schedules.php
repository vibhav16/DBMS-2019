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
	<h3>Get Schedule By: </h3>
	<p>
		<?php
			$cmd = 'SELECT T.TEAMNAME as TN FROM TEAM T ORDER BY TN';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlTeams" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlTeams">'; // Open your drop down box

			// Loop through the query results, outputing the options one by one
			$data  = 'ANY TEAM';
			echo '<option value="'.$data.'">'.$data.'</option>';
			while (oci_fetch($statement)) {
				$data  = oci_result($statement,'TN');
				echo '<option value="'.$data.'">'.$data.'</option>';
			}
			
			echo '</select>';
			//
			// VERY important to close Oracle Database Connections and free statements!
			//
			//oci_free_statement($statement);
			//oci_close($connection);
		?>
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
	<p>
	</p>
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
		<input type="text" class="btn btn-outline-primary btn-rounded waves-effect" name="datepicker" id="datepicker" value="01/01/1970">
		<input type="checkbox" name="cb1" class="btn btn-outline-primary btn-rounded waves-effect" id="cb1">Pick just month and year
	</p>		
	<input type="submit" name="getSch" value="Get Schedule" class="btn btn-info btn-rounded" onclick="getSch()" />	
	<input type="submit" name="clearSch" value="Clear Schedule" class="btn btn-info btn-rounded" onclick="window.location.reload()" />	
	
	<br>
	<br>
	<h3>Find all matches with:</h3>
	<p>
		<?php
			// home team
			$cmd = 'SELECT T.TEAMNAME as TN FROM TEAM T ORDER BY TN';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlHome" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlHome">'; // Open your drop down box

			// Loop through the query results, outputing the options one by one
			$data  = 'HOME TEAM';
			echo '<option value="'.$data.'">'.$data.'</option>';
			while (oci_fetch($statement)) {
				$data  = oci_result($statement,'TN');
				echo '<option value="'.$data.'">'.$data.'</option>';
			}
			echo '</select>';
			// Away team
			$cmd = 'SELECT T.TEAMNAME as TN FROM TEAM T ORDER BY TN';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlAway" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlAway">'; // Open your drop down box

			// Loop through the query results, outputing the options one by one
			$data  = 'AWAY TEAM';
			echo '<option value="'.$data.'">'.$data.'</option>';
			while (oci_fetch($statement)) {
				$data  = oci_result($statement,'TN');
				echo '<option value="'.$data.'">'.$data.'</option>';
			}
			echo '</select>';
			
			//win or lost
			echo '<select id="ddlResult" class="btn btn-outline-primary btn-rounded waves-effect" name="ddlResult">';
			echo '<option value="any">Any Outcome</option>';
			echo '<option value="ht">Home team won</option>';
			echo '<option value="at">Away team won</option>';
			echo '<option value="htot">Home team won in Overtime</option>';
			echo '<option value="atot">Away team won in Overtime</option>';
			echo '</select>';
		?>
	<p>
	<input type="submit" name="getSch1" value="Get Schedule" class="btn btn-info btn-rounded" onclick="getSch1()" />	
	<input type="submit" class="btn btn-info btn-rounded" name="clearSch1" value="Clear Schedule" onclick="window.location.reload()" />	
	
	<p>
		<?php
			// GET SQL QUERY AS PER USER REQUESTS
			// ---------------------------------------------------
			if(array_key_exists('getSch',$_POST)){
			    $t = $_POST['ddlTeams']; 
				$v = $_POST['ddlVenues'];
				$s = $_POST['ddlSeasons'];
				$origDate = $_POST['datepicker'];
				// use below extra line for british date : dd/mm/yyy
			    //$origDate = str_replace('/', '-', $origDate ); 
				$d = date("Y-m-d", strtotime($origDate));
				
			    echo "\n Matches for team: ";
			    echo $t;
				$cmd = "SELECT T1.TEAMNAME as TN1, 
							T2.TEAMNAME as TN2, 
							G.DATE_TIME as DT,
							G.VENUE 
							FROM GAME G, TEAM T1, TEAM T2
							WHERE ";
				if ($t != 'ANY TEAM') {
					$cmd .= "T1.TEAMNAME = '$t' AND ";
				} 
				if ($v != 'ANY VENUE') {
					$cmd .=  "G.VENUE = '$v' AND ";
				}
				if ($s != 'ANY SEASON') {
					$cmd .=  "G.SEASON = '$s' AND ";
				}
				if ($d != '1970-01-01') {
					if (isset($_POST['cb1'])) {
						// Checkbox is selected
						$d = date("Y-m", strtotime($d));
					}	
					$cmd .=  "G.DATE_TIME LIKE '$d%' AND ";
				}
				$cmd .= "T1.TEAM_ID = G.HOME_TEAM_ID AND
						 T2.TEAM_ID = G.AWAY_TEAM_ID
						ORDER BY DT DESC";
				//echo $cmd;
				getSchedule($connection, $cmd);
			} 
			if(array_key_exists('getSch1',$_POST)){
				$ht = $_POST['ddlHome']; 
				$at = $_POST['ddlAway'];
				$wl = $_POST['ddlResult'];
				if (($ht == 'HOME TEAM') && ($at == 'AWAY TEAM')){
					echo '<span style="color:red;">Please select either Home Team or Away Team!</span>';
				}
				else {
					$cmd = "SELECT T1.TEAMNAME as TN1, 
						T2.TEAMNAME as TN2, 
						G.DATE_TIME as DT,
						G.VENUE 
						FROM GAME G, TEAM T1, TEAM T2
						WHERE ";
					if ($ht != 'HOME TEAM'){
						$cmd .= "T1.TEAMNAME = '$ht' AND ";
					}
					if ($at != 'AWAY TEAM'){
						$cmd .= "T2.TEAMNAME = '$at' AND ";
					}
					if ($wl == 'ht'){
						$cmd .= "G.OUTCOME = 'home win REG' AND ";
					} else if ($wl == 'ht'){
						$cmd .= "G.OUTCOME = 'away win REG' AND ";
					} else if($wl == 'ht'){
						$cmd .= "G.OUTCOME = 'home win OT' AND ";
					} else if($wl == 'ht'){
						$cmd .= "G.OUTCOME = 'away win OT' AND ";
					} 
					
					$cmd .= "G.HOME_TEAM_ID = T1.TEAM_ID AND
						     G.AWAY_TEAM_ID = T2.TEAM_ID
						     ORDER BY DT DESC";
					//echo $cmd;
					getSchedule($connection, $cmd);
				}
			}
			
			function getSchedule($connection, $cmd){
			// DISPLAY THE RETRIEVED TABLE
			// ---------------------------------------------------
				$statement = oci_parse($connection, $cmd);
				oci_execute($statement);

				echo "<table border=\"1\">\n";
				echo "<tr>";
				echo "<th>Home Team</th>";
				echo "<th>Away Team</th>";
				echo "<th>Date and time</th>";
				echo "<th>Venue</th>";
				echo "</tr>\n";

				$ncols = oci_num_fields($statement);
				while (oci_fetch($statement)) {
					echo "<tr>";
					for ($i = 1; $i <= $ncols; $i++) {
						if ($i==1) $data  = oci_result($statement,'TN1');
						else if ($i==2) $data  = oci_result($statement, 'TN2');
						else if ($i==3) $data  = oci_result($statement, 'DT');
						else if ($i==4) $data  = oci_result($statement, 'VENUE');
						//$data  = oci_field_name($statement, $val);
						echo "<td>$data</td>";
						
					}
					echo "</tr>\n";
				}
				echo "</table>\n";
			}
			// ---------------------------------------------------
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