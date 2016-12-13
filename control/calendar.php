<?
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once "model/calendar.class.php";
$calendar = new calendarClass();
$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : 'home';

switch($op)
{
	case 'home':
	$events=$calendar->listaCalendar();
	Reg::$out->assign('events', $events);

	Reg::$out->assign('content', "calendar/home");
    echo Reg::$out->display('layouts/login.tpl');
	break;

	case 'add_event':
	if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){

		if (!empty($_POST['title']) && !empty($_POST['start']) && !empty($_POST['end']) && !empty($_POST['color'])) {
			$event=$calendar->addEvent();
			Reg::$out->assign('event', $event);
		}
	}

	Reg::$out->assign('resposta', 'ok');
    echo Reg::$out->display('layouts/json.tpl');
	break;

	case 'edit_event':
	$edit=$calendar->editEvent();

	Reg::$out->assign('edit', $edit);

	Reg::$out->assign('resposta', 'ok');
	echo Reg::$out->display('layouts/json.tpl');
	break;

	case 'editEventDate':
	$editEvent=$calendar->editEventDate();

	Reg::$out->assign('editEvent', $editEvent);

	Reg::$out->assign('resposta', 'ok');
	echo Reg::$out->display('layouts/json.tpl');
	break;

}