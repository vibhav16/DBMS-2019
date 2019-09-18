<?php    
include("includes/a_config.php");
include("includes/connectionSettings.php");?>
<?php $param = $_GET['teamid']; 
if(isset($_POST['reset'])){
    unset($_POST); 
} ?>

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


<h2 style="padding-top:50px">SKATERS IN THE TEAM</h2>
<div>


<?php echo "<form action='/players.php?teamid=$param' method='post'>"?>
<select name="Country"> 
<?php $options = oci_parse($connection, "SELECT DISTINCT NATIONALITY FROM PLAYER P 
INNER JOIN GAME_SKATER_STATS GSS ON P.PLAYER_ID = GSS.PLAYER_ID WHERE TEAM_ID ='$param'"); 
    oci_execute($options)
    ?>
    <option>Country</option>
    <?php while ($option = oci_fetch_array($options)) { ?>
        <?php $nationality = $option['NATIONALITY']; ?>
		<?php echo "<option value='$nationality'>  $nationality </option>"?>
    </option>
	<?php } ?>
</select>


<select name="Position"> 
<?php $options = oci_parse($connection, "SELECT DISTINCT PRIMARYPOSITION FROM PLAYER P 
INNER JOIN GAME_SKATER_STATS GSS ON P.PLAYER_ID = GSS.PLAYER_ID WHERE TEAM_ID ='$param'"); 
    oci_execute($options)
    ?>
    <option>Position</option>
    <?php while ($option = oci_fetch_array($options)) { ?>
        <?php $position = $option['PRIMARYPOSITION']; ?>
		<?php echo "<option value='$position'>  $position </option>"?>
    </option>
	<?php } ?>
</select>

<select name="LastName"> 
<?php $options = oci_parse($connection, "SELECT DISTINCT LASTNAME FROM PLAYER P 
INNER JOIN GAME_SKATER_STATS GSS ON P.PLAYER_ID = GSS.PLAYER_ID WHERE TEAM_ID ='$param'"); 
    oci_execute($options)
    ?>
    <option>Last Name</option>
    <?php while ($option = oci_fetch_array($options)) { ?>
        <?php $lastname = $option['LASTNAME']; ?>
		<?php echo "<option value='$lastname'>  $lastname </option>"?>
    </option>
	<?php } ?>
</select>

<input type="submit" name="submit" value="Get Selected Values" />
<input type="submit" name="reset" value="Reset Filters" />
</form>
<?php
if(isset($_POST['submit'])){
    if(isset($_POST['Country']) && $_POST['Country'] != "Country"){
$selected_country = $_POST['Country']; 
    }
    if(isset($_POST['Position']) && $_POST['Position'] != "Position"){
$selected_position = $_POST['Position']; 
    }
    if(isset($_POST['LastName']) && $_POST['LastName'] != "Last Name"){
$selected_lastname = $_POST['LastName']; 
     } 
}
?>

	<p>
    <?php $extraQuery = ""; 
    if(isset($selected_country)){
        $extraQuery = $extraQuery. " AND NATIONALITY='$selected_country'";
    }
    if(isset($selected_position)){
        $extraQuery = $extraQuery. " AND PRIMARYPOSITION='$selected_position'";
    }
    if(isset($selected_lastname)){
        $extraQuery = $extraQuery. " AND LASTNAME='$selected_lastname'";
    }
    ?>
	<?php $results = oci_parse($connection, "SELECT DISTINCT FIRSTNAME, LASTNAME, NATIONALITY, BIRTHCITY, PRIMARYPOSITION, BIRTHDATE FROM PLAYER P 
INNER JOIN GAME_SKATER_STATS GSS ON P.PLAYER_ID = GSS.PLAYER_ID WHERE TEAM_ID ='$param'".$extraQuery); 
   
    oci_execute($results)
    ?>
<table class="table">
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
            <th>Nationality</th>
            <th>Birthcity</th>
            <th>Primary Position</th>
            <th>Birthdate</th>
		</tr>
	</thead>
	<tbody>
	
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['FIRSTNAME']; ?></td>
			<td><?php echo $row['LASTNAME']; ?></td>
            <td><?php echo $row['NATIONALITY']; ?></td>
            <td><?php echo $row['BIRTHCITY']; ?></td>
            <td><?php echo $row['PRIMARYPOSITION']; ?></td>
            <td><?php echo $row['BIRTHDATE']; ?></td>
    
		</tr>
	<?php } ?>
	</tbody>
</table>
</p>
</div>

<h2 style="padding-top:50px">GOALIES IN THE TEAM</h2>
<div>
	<p>
    <?php $param = $_GET['teamid']; ?>
	<?php $results = oci_parse($connection, "SELECT DISTINCT FIRSTNAME, LASTNAME, NATIONALITY, BIRTHCITY, PRIMARYPOSITION, BIRTHDATE FROM PLAYER P 
INNER JOIN GAME_GOALIE_STATS GGS ON P.PLAYER_ID = GGS.PLAYER_ID WHERE TEAM_ID ='$param'". $extraQuery); 
    oci_execute($results)
    ?>
<table class="table">
	<thead>
		<tr>
			<th>First Name</th>
			<th>Last Name</th>
            <th>Nationality</th>
            <th>Birthcity</th>
            <th>Primary Position</th>
            <th>Birthdate</th>
		</tr>
	</thead>
	<tbody>
	
	<?php while ($row = oci_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['FIRSTNAME']; ?></td>
			<td><?php echo $row['LASTNAME']; ?></td>
            <td><?php echo $row['NATIONALITY']; ?></td>
            <td><?php echo $row['BIRTHCITY']; ?></td>
            <td><?php echo $row['PRIMARYPOSITION']; ?></td>
            <td><?php echo $row['BIRTHDATE']; ?></td>
    
		</tr>
	<?php } ?>
	</tbody>
</table>
	
</p>
</div>
<?php include("includes/footer.php");?>
</body>
</html>