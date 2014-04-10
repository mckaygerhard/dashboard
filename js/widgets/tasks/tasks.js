$(document).ready(function() { bindMarkAsRead(); });


// bind mark as read action
function bindMarkAsRead() {
	$('.ocDashboard.tasks.item span').each(function(i, current){
			tmp = current.id.split("-");
			id = tmp[1];
			$("#task-" + id).live('click',function(){
					tmp = this.id.split("-");
					id = tmp[1];
					markAsRead(id);
				}
			);
		}
	);	
}


// ajax action for mar as read
function markAsRead(id) {
	showWaitSymbol('tasks');
	$("#task-" + id).parent().fadeOut();
	ajaxService('tasks',
				'markAsDone',
				id,
				function(res) {
					loadWidget('tasks');
				}
	);
}