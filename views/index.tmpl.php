<!--
@Author: belst
@Date:   20-09-2016
@Email:  gsm@bel.st
@Last modified by:   belst
@Last modified time: 20-09-2016
@License: BSD3
-->


<?php include '_partials/header.php'; ?>

<form method="POST" action="index.php" class="form-search">
	<input name="q" placeholder="Name or GUID" class="search-query" type="text">
	<button class="btn" type="submit">Search</button>
</form>

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<td>Rank</td>
			<td>Name</td>
			<td>Kills</td>
			<td>Deaths</td>
			<td>K/D</td>
			<td>Headshots</td>
			<td>HS Ratio</td>
		</tr>
	</thead>
	<tbody class="userlist">
	<?php

	$toshow = 30;
	$totalpages = round($users[0]->totalrows / $toshow);
	$next = ($page + 1 <= $totalpages) ? $page + 1 : $page;
	$prev = ($page - 1 > 0) ? $page -1 : $page;

	$rank = ($page - 1) * $toshow;

	foreach($users as $u) {
		$rank++;

		$nicks = htmlentities($u->nick);

		if($u->kd) {
			$kd = round($u->kd, 2);
		} elseif($u->kills != 0) {
			$kd = "Infinity";
		} else {
			$kd = 0;
		}

		$hsratio = ( $u->hsr ) ? round($u->hsr, 2) : 0;

		echo "<tr data-user_id=\"{$u->id}\"><td>$rank</td><td><a href=\"user.php?id={$u->id}\">{$nicks}</a></td><td>{$u->kills}</td><td>{$u->deaths}</td><td>{$kd}</td><td>{$u->headshots}</td><td>{$hsratio} %</td></tr>";
	}

	echo "<tr><td colspan=\"7\" style=\"text-align:center;\"><a href=\"index.php?page=1\">&lt;&lt;First Page</a> <a href=\"index.php?page={$prev}\">&lt; Prev Page</a> | <a href=\"index.php?page={$next}\">Next Page &gt;</a> <a href=\"index.php?page={$totalpages}\">Last Page &gt;&gt;</a></td></tr>
	<tr><td colspan=\"7\">Page {$page} of {$totalpages}</td></tr>";
	?>

	<script id="user_list_template" type="text/x-handlebars-template">
		{{#each this}}
		<tr data-user_id={{id}}>
			<td><a href=user.php?id={{id}}>{{nicks}}</a></td>
			<td>{{kills}}</td>
			<td>{{deaths}}</td>
			<td>{{kd this}}</td>
			<td>{{headshots}}</td>
			<td>{{hsr this}} %</td>
		</tr>
		{{/each}}
	</script>
	</tbody>
</table>


<div id="myModal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" data-target="myModal">&times;</button>
			<h3 id="myModalLabel"><i class="icon-user"></i> Nicknames</h3>
		</div>
		<div class="modal-body">
			<p>
				<ul id="userinfo">
					<script id="user_info_template" type="text/x-handlebars-template">
					{{#each this}}
						<li>{{nick}}</li>
					{{/each}}
					</script>
				</ul>
			</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" data-target="myModal" >Close</button>
		</div>
	</div>

<?php include '_partials/footer.php'; ?>
