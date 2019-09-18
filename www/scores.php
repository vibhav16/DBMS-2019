<?php    
include("includes/a_config.php");
include("includes/connectionSettings.php");?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<?php include("includes/head-tag-contents.php");?>
	<style>
}

tr:hover {
    background: #F5F5F5;
}

</style>
</head>
<body>


<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>



<h2 style="padding-top:50px">Scores by Team</h2>
<div>


<?php echo "<form action='/scores.php' method='post'>"?>

<select name="TeamName"> 
<?php $options = oci_parse($connection, "select distinct b.teamname from game_teams_stats a, team b where a.team_id=b.team_id
group by b.teamname"); 
    oci_execute($options)
    ?>
    <option>Team Name</option>
    <?php while ($option = oci_fetch_array($options)) { ?>
        <?php $teamname = $option['TEAMNAME']; ?>
		<?php echo "<option value='$teamname'>  $teamname </option>"?>
    </option>
	<?php } ?>
</select>

<input type="submit" name="submit" value="Get Selected Teams" />
</form>
<?php
if(isset($_POST['submit'])){
    if(isset($_POST['TeamName']) && $_POST['TeamName'] != "Team Name"){
$selected_teamname = $_POST['TeamName']; 

	} 
}
?>
	<p>
    <?php $extraQuery = ""; 
    
    if(isset($selected_teamname)){
        $extraQuery = $extraQuery. " AND TEAMNAME='$selected_teamname'";
    }
	?>
	<?php $stmt= "select distinct b.teamname, sum(a.goals) as Total_Points from game_teams_stats a, team b where a.team_id=b.team_id ".$extraQuery.
" group by b.teamname"?>
	<?php $results = oci_parse($connection, $stmt); 
  // echo $stmt;
    oci_execute($results)
    ?>
<table class="table">
	<thead>
		<tr>
			<th>Team Name</th>
			<th>Total Points</th>
            
		</tr>
	</thead>
	<tbody>
	
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['TEAMNAME']; ?></td>
			<td><?php echo $row['TOTAL_POINTS']; ?></td>
    
		</tr>
	<?php } ?>
	</tbody>
</table>
</p>
</div>

<h2 style="padding-top:50px">Scores by Venue</h2>
<div>


<?php echo "<form action='/scores.php' method='post'>"?>

<select name="Venue"> 
<?php $options = oci_parse($connection, "select distinct a.venue from game a, game_teams_stats b where a.game_id=b.game_id
group by a.venue"); 
    oci_execute($options)
    ?>
    <option>Venue</option>
    <?php while ($option = oci_fetch_array($options)) { ?>
        <?php $venue = $option['VENUE']; ?>
		<?php echo "<option value='$venue'>  $venue </option>"?>
    </option>
	<?php } ?>
</select>

<input type="submit" name="submit" value="Get Selected Venues" />
</form>
<?php
if(isset($_POST['submit'])){
    if(isset($_POST['Venue']) && $_POST['Venue'] != "Venue"){
$selected_venue = $_POST['Venue']; 

	} 
}
?>
	<p>
    <?php $extraQuery = ""; 
    
    if(isset($selected_venue)){
        $extraQuery = $extraQuery. " AND VENUE='$selected_venue'";
    }
	?>
	<?php $stmt= "select distinct a.venue, sum(b.goals) as Total_Score from game a, game_teams_stats b where a.game_id=b.game_id".$extraQuery.
" group by a.venue"?>
	<?php $results = oci_parse($connection, $stmt); 
   //echo $stmt;
	oci_execute($results);
	
    ?>
<table class="table">
	<thead>
		<tr>
			<th>Venue</th>
			<th>Total Score</th>
            
		</tr>
	</thead>
	<tbody>
	
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['VENUE']; ?></td>
			<td><?php echo $row['TOTAL_SCORE']; ?></td>
    
		</tr>
	<?php } ?>
	</tbody>
</table>
</p>
</div>




<h2 style="padding-top:50px">Scores by Season</h2>
<div>
<?php echo "<form action='/scores.php' method='post'>"?>

<select name="Season"> 
<?php $options = oci_parse($connection, "select distinct a.season from game a, game_teams_stats b where a.game_id=b.game_id
group by a.season"); 
    oci_execute($options)
    ?>
    <option>Season</option>
    <?php while ($option = oci_fetch_array($options)) { ?>
        <?php $season = $option['SEASON']; ?>
		<?php echo "<option value='$season'>  $season </option>"?>
    </option>
	<?php } ?>
</select>

<input type="submit" name="submit" value="Get Selected Seasons" />
</form>
<?php
if(isset($_POST['submit'])){
    if(isset($_POST['Season']) && $_POST['Season'] != "Season"){
$selected_season = $_POST['Season']; 

	} 
}
?>
	<p>
    <?php $extraQuery = ""; 
    
    if(isset($selected_season)){
        $extraQuery = $extraQuery. " AND SEASON='$selected_season'";
    }
	?>
	<?php $stmt= "select distinct a.season, sum(b.goals) as Total_Score from game a, game_teams_stats b where a.game_id=b.game_id".$extraQuery.
" group by a.season"?>
	<?php $results = oci_parse($connection, $stmt); 
   //echo $stmt;
	oci_execute($results);
	
    ?>
<table class="table">
	<thead>
		<tr>
			<th>Venue</th>
			<th>Total Score</th>
            
		</tr>
	</thead>
	<tbody>
	
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['SEASON']; ?></td>
			<td><?php echo $row['TOTAL_SCORE']; ?></td>
    
		</tr>
	<?php } ?>
	</tbody>
</table>
</p>
</div>
<?php include("includes/footer.php");?>
</body>
</html>