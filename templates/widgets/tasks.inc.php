<?php 
	$colors = Array(9 => "black", 5 => "darkgreen", 1 => "darkred");
?>

<div class='ocDashboard tasks items'>
    <div class="newtask">
        <form action="" method="">
            <input type="text" size="20" id="addTaskSummary" name="addTaskSummary" />
            <select id="addTaskPriority" name="addTaskPriority">
                <option value="9">normal</option>
                <option value="5">more important</option>
                <option value="1">most important</option>
            </select>
            <select id="addTaskCalendarId" name="addTaskCalendarId">
            <?php
            foreach ($additionalparams['calendars'] as $k => $v) {
                    print_unescaped('<option value="'.$v['id'].'">'.$v['displayname'].'</option>');
                }
            ?>
            </select>
            <input type="submit" value="Add" id="addTaskSubmit">
        </form>
    </div>

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

<div id="addTask">+</div>