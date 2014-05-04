$(document).ready(function() { bindMarkAsRead(); });


// bind mark as read action
function bindMarkAsRead() {
	$('.ocDashboard.tasks.item span').each(function(i, current){
			tmp = current.id.split("-");
			id = tmp[1];
            // TODO use on() instead of live()
			$("#task-" + id).live('click',function(){
					tmp = this.id.split("-");
					id = tmp[1];
					markAsRead(id);
				}
			);
		}
	);

    $('#addTask').live('click', function(event) {
        $(".newtask").slideDown();
    });

    $('#addTaskSubmit').live('click', function(event) {
        newTask();
        event.preventDefault();
    });
}


// ajax action for mar as read
function markAsRead(id) {
	showWaitSymbol('tasks');
	$("#task-" + id).parent().fadeOut();
	ajaxService('tasks',
				'markAsDone',
				id,
				function(res) {
					//loadWidget('tasks');
                    bindMarkAsRead();
				}
	);
    hideWaitSymbol('tasks');
}

function newTask() {
    showWaitSymbol('tasks');
    var value = $("#addTaskSummary").val() + "#|#" + $("#addTaskPriority").val() + "#|#" + $("#addTaskCalendarId").val();
    alert(value);
    ajaxService('tasks',
        'newTask',
        value,
        function(res) {
            loadWidget('tasks');
        }
    );
}