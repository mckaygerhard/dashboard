<div class="ocDashboard calendar items">

	<?php

	foreach ($additionalparams['activitys'] as $activity)
	{	
		print_unescaped(
            "<div class='priority".$activity['priority']." activity-entry'>".
                \OC_Util::sanitizeHTML($activity['subject']).
                "<span>".
                    \OCP\relative_modified_date($activity['timestamp']).
                "</span>
            </div>
        ");
	}
	?>		
		
</div>