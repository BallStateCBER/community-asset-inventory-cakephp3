<?php
    /** @var array $scores */
?>
<?php if (!$scores['grade']): ?>
	<ul id="legend-index">
		<li class="index-115">115.0-129.9</li>
		<li class="index-105">105.0-114.9</li>
		<li class="index-95">95.0-104.9</li>
		<li class="index-85">85.0-94.9</li>
		<li class="index-70">70.0-84.9</li>
	</ul>
<?php else: ?>
	<ul id="legend-grades">
		<li class="grade-a">A</li>
		<li class="grade-b">B</li>
		<li class="grade-c">C</li>
		<li class="grade-d">D</li>
		<li class="grade-f">F</li>
	</ul>
<?php endif; ?>
