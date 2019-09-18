<?php 
	session_start();
	if (isset($_POST['Query2'])) {
		$_SESSION['page'] = "Query2";
	}
	else if (isset($_POST['Query3'])) {
		$_SESSION['page'] = "Query3";
	}
	else if (isset($_POST['Query3'])) {
		$_SESSION['page'] = "Query3";
	}
	else if (isset($_POST['Query4'])) {
		$_SESSION['page'] = "Query4";
	}
	else{
	$_SESSION['page'] = "Query1";
	}
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

html, body{
	height: 100%;
}
/* Style the tab */
.tab {
  position:relative;
  float: left;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
  width: 20%;
  height: 100%;
  border-radius:.25rem;
  
}
/* Style the buttons inside the tab */
.tab button {
  display: block;
  background-color: inherit;
  color: black;
  padding: 22px 16px;
  width: 100%;
  border: none;
  outline: none;
  text-align: left;
  cursor: pointer;
  transition: 0.3s;
  font-size: 17px;
}
/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}
/* Create an active/current "tab button" class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  float: left;
  padding: 0px 12px;
  width: 79%;
  border-left: none;
  height: 300px;
}
</style>
</head>
<body>



<div class="tab">
<form class="form" action=teams.php method="post">

  <button class="tablinks" style="background-color:#007bff;color:white;border-radius:.25rem">Interesting Insights!</button>
  <button class="tablinks" style="border-radius:.25rem" type="submit" name="Query1">Teams & Players</button>
  <button class="tablinks" style="border-radius:.25rem" type="submit" name="Query2">Names of players who are more than 30 and play on the wing (LW or RW) 
  and has his shot blocked the most times (Top 10)</button>
  <button class="tablinks" style="border-radius:.25rem" type="submit" name="Query3">Players who have score goals who play on X position and has scored goals only with 'snap shot' and the goals are 
  only powerplay goals and the team has won eventually (Top 10)</button>

</form>
</div>

<div class="container tabcontent" id="main-content">
<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>
	
	
<?php 
if(isset($_SESSION['page']) && $_SESSION['page'] == "Query1"){
?>

<h2 style="padding-top:50px">Teams</h2>
	<p>
	<?php $results = oci_parse($connection, "SELECT * FROM team"); 
	oci_execute($results)?>
<table class="table">
	<thead>
		<tr>
			<th>Team Name</th>
			<th>Short Name</th>
			<th>Abbreviation</th>
			<th>Matches Won</th>
		</tr>
	</thead>
	<tbody>
	
	<?php while ($row = oci_fetch_array($results)) { ?>
		
			<?php $param = $row['TEAM_ID'] ?>
			
		<tr>
			
		<?php 
		$stmt = "select count(b.game_id) as matches_won from team a, game_teams_stats b where 
		a.team_id=b.team_id and a.team_id='$param' and b.won='TRUE'";
		$results1 = oci_parse($connection, $stmt); 
		//echo $stmt;
	oci_execute($results1);
	$match = oci_fetch_array($results1);
	?>
			<td ><?php echo "<a href='/players.php?teamid=$param'>"?><?php echo $row['TEAMNAME']; ?><?php echo "</a>";?></td>
			<td><?php echo $row['SHORTNAME']; ?></td>
			<td><?php echo $row['ABBREVIATION']; ?></td>
            <td> <?php echo $match['MATCHES_WON']; ?></td>
			
		</tr>
	</a>
	<?php } ?>
	</tbody>
</table>
	<?php } ?>
<?php 
if(isset($_SESSION['page']) && $_SESSION['page'] == "Query2"){
?>
<h2 style="margin-top:50px">Wing positioned players who get blocked the most</h2>
	<p>
	<?php
	$stmt = "select a.firstname, a.lastname from player a, game_plays_players b, game_plays c 
	where a.player_id=b.player_id and b.game_id=c.game_id and
	a.player_ID in (select player_id from player where (2019-extract(year from birthdate))>=25) and a.primaryposition like '_W'
	and c.event='Blocked Shot'
	group by a.firstname, a.lastname
	order by count(c.event) desc
	fetch first 10 rows only";
	$results = oci_parse($connection, $stmt); 
	oci_execute($results)?>
<table class="table">
	<thead>
		<tr>
			<th scope="col">FIRST NAME</th>
			<th scope="col">LAST NAME</th>
		</tr>
	</thead>
	<tbody>
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['FIRSTNAME']; ?></td>
			<td><?php echo $row['LASTNAME']; ?></td>
	
		</tr>
	</tbody>
	<?php } ?>
</table>
	<?php } ?>
	</p>

<?php 
if(isset($_SESSION['page']) && $_SESSION['page'] == "Query3"){
?>
<h2 style="margin-top:50px"></h2>
	<p>
	<?php
	$stmt = "select a.firstname,a.lastname from player a, game_skater_stats b, game_plays c, game_teams_stats d 
	where a.player_id=b.player_id and b.game_id=c.game_id and c.game_id=d.game_id and c.secondarytype='Snap Shot' 
	and b.goals=b.powerplaygoals and d.won='TRUE' and a.primaryposition='RW'
	group by a.firstname,a.lastname
	order by count(b.powerplaygoals) desc
	fetch first 10 rows only";
	$results = oci_parse($connection, $stmt); 
	oci_execute($results)?>
<table class="table">
	<thead>
		<tr>
			<th scope="col">FIRST NAME</th>
			<th scope="col">LAST NAME</th>
		</tr>
	</thead>
	<tbody>
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['FIRSTNAME']; ?></td>
			<td><?php echo $row['LASTNAME']; ?></td>
	
		</tr>
	</tbody>
	<?php } ?>
</table>
	<?php } ?>
	</p>
</div>
</body>
</html>