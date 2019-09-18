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
<div class="container" id="mainNHL">
	<h2>Schedules</h2>
	<p> Get schedule by Team
		<?php
			$cmd = 'SELECT T.TEAMNAME as TN FROM TEAM T';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlTeams" name="ddlTeams">'; // Open your drop down box

			// Loop through the query results, outputing the options one by one
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
		<input type="submit" name="getSch1" value="Get Matches" onclick="getSch1()" />
	</p>
	<p> Get schedule by Venue
		<?php
			$cmd = 'SELECT DISTINCT G.VENUE FROM GAME G';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlVenues" name="ddlVenues">'; // Open your drop down box

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
		<input type="submit" name="getSch2" value="Get Matches" onclick="getSch2()" />
	</p>
	<p> Get schedule by Season
		<?php
			$cmd = 'SELECT DISTINCT G.SEASON FROM GAME G';
			$statement = oci_parse($connection, $cmd);
			oci_execute($statement);
			echo '<select id="ddlSeasons" name="ddlSeasons">'; // Open your drop down box

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
		<input type="submit" name="getSch3" value="Get Matches" onclick="getSch3()" />
	</p>
	<p>
		Get schedule by Date
		<input type="text" runat="server" name="datepicker" id="datepicker">
		<input type="submit" name="getSch4" value="Get Matches" onclick="getSch4()" />
	
	</p>
	<p>
		<?php
			// below line outside php for normal calender
			// <input type="text" runat="server" name="datepicker" id="datepicker">
			// lowest year wanted
			$cutoff = 1910;

			// current year
			$now = date('Y');

			// build years menu
			echo '<select name="year">' . PHP_EOL;
			for ($y=$now; $y>=$cutoff; $y--) {
				echo '  <option value="' . $y . '">' . $y . '</option>' . PHP_EOL;
			}
			echo '</select>' . PHP_EOL;

			// build months menu
			echo '<select name="month">' . PHP_EOL;
			for ($m=1; $m<=12; $m++) {
				echo '  <option value="' . $m . '">' . date('M', mktime(0,0,0,$m)) . '</option>' . PHP_EOL;
			}
			echo '</select>' . PHP_EOL;

			// build days menu
			echo '<select name="day">' . PHP_EOL;
			for ($d=1; $d<=31; $d++) {
				echo '  <option value="' . $d . '">' . $d . '</option>' . PHP_EOL;
			}
			echo '</select>' . PHP_EOL;
		?>
	</p>
	<p>
		<?php
			// GET SQL QUERY AS PER USER REQUEST
			// ---------------------------------------------------
			/*	 ORIGINAL		
			$cmd = 'SELECT T1.TEAMNAME as TN1,
					   T2.TEAMNAME as TN2,
					   G.DATE_TIME as DT,
					   G.VENUE 
					   FROM GAME G, TEAM T1, TEAM T2 
					   WHERE G.AWAY_TEAM_ID = T1.TEAM_ID 
					   AND G.HOME_TEAM_ID = T2.TEAM_ID';
			*/			
			
			if(array_key_exists('getSch1',$_POST)){
			    $t = $_POST['ddlTeams']; 
			    echo "\n Matches for team: ";
			    echo $t;
			    $cmd = "SELECT T1.TEAMNAME as TN1, 
						T2.TEAMNAME as TN2, 
						G.DATE_TIME as DT,
						G.VENUE 
						FROM GAME G, TEAM T1, TEAM T2
						WHERE T1.TEAMNAME = '$t'
						AND G.HOME_TEAM_ID = T1.TEAM_ID
						AND T2.TEAM_ID = G.AWAY_TEAM_ID
						ORDER BY DT DESC";
				getSchedule($connection, $cmd);
			} else if (array_key_exists('getSch2',$_POST)){
			    $v = $_POST['ddlVenues'];
			    echo "\n Matches for venue: ";
			    echo $v;
			    $cmd = "SELECT T1.TEAMNAME as TN1, 
						T2.TEAMNAME as TN2, 
						G.DATE_TIME as DT,
						G.VENUE 
						FROM GAME G, TEAM T1, TEAM T2
						WHERE G.VENUE = '$v'
						AND T1.TEAM_ID = G.HOME_TEAM_ID
						AND T2.TEAM_ID = G.AWAY_TEAM_ID
						ORDER BY DT DESC";
				getSchedule($connection, $cmd);
			} else if (array_key_exists('getSch3',$_POST)){
			    $s = $_POST['ddlSeasons'];
			    echo "\n Matches for season: ";
			    echo $s;
			    $cmd = "SELECT T1.TEAMNAME as TN1, 
						T2.TEAMNAME as TN2, 
						G.DATE_TIME as DT,
						G.VENUE 
						FROM GAME G, TEAM T1, TEAM T2
						WHERE G.SEASON = $s
						AND T1.TEAM_ID = G.HOME_TEAM_ID
						AND T2.TEAM_ID = G.AWAY_TEAM_ID
						ORDER BY DT DESC";
				getSchedule($connection, $cmd);
			} else if (array_key_exists('getSch4',$_POST)){
			    $d = $_POST['datepicker'];
			    echo "\n Matches for date: ";
			    //echo $d;
			    $date = str_replace('/', '-', $d );
				$newDate = date("Y-m-d", strtotime($date));
				echo $newDate;
				$cmd = "SELECT T1.TEAMNAME as TN1, 
						T2.TEAMNAME as TN2, 
						G.DATE_TIME as DT,
						G.VENUE 
						FROM GAME G, TEAM T1, TEAM T2
						WHERE G.DATE_TIME LIKE '$newDate%'
						AND T1.TEAM_ID = G.HOME_TEAM_ID
						AND T2.TEAM_ID = G.AWAY_TEAM_ID
						ORDER BY DT DESC";
				getSchedule($connection, $cmd);
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