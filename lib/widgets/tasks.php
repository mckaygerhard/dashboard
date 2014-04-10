<?php
/*
 * displays tasks from Taskapp by ownCloud
 * copyright 2013
 *
 * @version 0.1
 * @date 01-08-2013
 * @author Florian Steffens (flost@live.no)
 */
class tasks extends widget implements interfaceWidget {

	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
        return Array("tasks" => $this->getTasks());
	}	
	
	// ======== END INTERFACE METHODS =============================

	
	/*
	 * called by ajaxService
	 * 
	 * @param $id of task
	 * @return if success
	 */
	public function markAsDone($id) {
        $vcalendar = OC_Calendar_App::getVCalendar( $id );
        $vtodo = $vcalendar->VTODO;
        OC_Task_App::setComplete($vtodo, '100', null);
        OC_Calendar_Object::edit($id, $vcalendar->serialize());
        return true;
	}
	

    private function getTasks() {
        $calendars = OC_Calendar_Calendar::allCalendars(OCP\User::getUser(), true);
        $user_timezone = OC_Calendar_App::getTimezone();

        $calendarTasks = array();
        foreach( $calendars as $calendar ) {
            $calendar_tasks = OC_Calendar_Object::all($calendar['id']);
            $tasks = array();
            foreach( $calendar_tasks as $task ) {
                if($task['objecttype']!='VTODO') {
                    continue;
                }
                if(is_null($task['summary'])) {
                    continue;
                }
                $object = OC_VObject::parse($task['calendardata']);
                $vtodo = $object->VTODO;
                try {
                    $t = OC_Task_App::arrayForJSON($task['id'], $vtodo, $user_timezone);
                    if($t['complete'] != "100") {
                        $tasks[] = $t;
                    }
                } catch(Exception $e) {
                    OCP\Util::writeLog('tasks', $e->getMessage(), OCP\Util::ERROR);
                }
            }
            $calendarTasks[$calendar['displayname']] = $tasks;
        }
        return $calendarTasks;
    }
	
}