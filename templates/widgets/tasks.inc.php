<?php 
	$colors = Array(9 => "darkblue", 5 => "darkgreen", 1 => "darkred");
?>
		
<div class='ocDashboard tasks items'>
			
<?php 
	foreach ($additionalparams['tasks'] as $k => $calendarTasks) {
        if(count($calendarTasks) > 0) {
            print_unescaped('<div class="task calendar">'.$k.'</div>');
        }
        foreach($calendarTasks as $task) {
            if(isset($task['priority']) && $task['priority'] != "") {
                $style = ' style="color: '.$colors[$task['priority']].'" ';
            } else {
                $style = "";
            }
?>
		
		<div class='ocDashboard tasks item' <?php print_unescaped($style); ?>>
        	<span id="task-<?php p($task['id']); ?>">&#10003;&nbsp;</span>
        	<?php p($task['summary']); ?>
        </div>
        <?php
	    }
    }
	?>
</div>