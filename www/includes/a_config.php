<?php
	switch ($_SERVER["SCRIPT_NAME"]) {
		case "/schedules.php":
			$CURRENT_PAGE = "Schedules"; 
			$PAGE_TITLE = "Schedules";
			break;
		case "/rankings.php":
			$CURRENT_PAGE = "Rankings"; 
			$PAGE_TITLE = "Rankings";
			break;
		case "/scores.php":
			$CURRENT_PAGE = "Scores"; 
			$PAGE_TITLE = "Teams";
			break;
		case "/teams.php":
			$CURRENT_PAGE = "Teams"; 
			$PAGE_TITLE = "Teams";
			break;
		case "/trends.php":
			$CURRENT_PAGE = "Trends"; 
			$PAGE_TITLE = "Trends";
			break;
		default:
			$CURRENT_PAGE = "Index";
			$PAGE_TITLE = "Welcome to my homepage!";
	}
?>