<div class="container">
	<ul class="nav nav-pills">
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Index") {?>active<?php }?>" href="index.php">Home</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Schedules") {?>active<?php }?>" href="schedules.php">Schedules</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Rankings") {?>active<?php }?>" href="rankings.php">Rankings</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Scores") {?>active<?php }?>" href="scores.php">Scores</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Teams") {?>active<?php }?>" href="teams.php">Insights</a>
		</li>
		<li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Trends") {?>active<?php }?>" href="trends.php">Trends</a>
	  </li>
	</ul>
</div>