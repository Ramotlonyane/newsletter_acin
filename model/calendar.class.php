<?
class calendarClass
{

	function listaCalendar()
	{
		$sql = "SELECT id, title, start, end, color FROM events where bDeleted = 0";
		$res = Reg::$db->queryArray($sql);

		return $res;
	}
	function addEvent(){

		if (isset($_REQUEST['title']) && isset($_REQUEST['start']) && isset($_REQUEST['end']) && isset($_REQUEST['color'])){

			if (!empty($_REQUEST['title']) && !empty($_REQUEST['start']) && !empty($_REQUEST['end']) && !empty($_REQUEST['color'])) {

				$title 	= $_REQUEST['title'];
				$start 	= $_REQUEST['start'];
				$end 	= $_REQUEST['end'];
				$color 	= $_REQUEST['color'];

				$sql = "INSERT INTO events(title, start, end, color) values ('$title', '$start', '$end', '$color')";
				
				$res = Reg::$db->queryArray($sql);

				return $res;
			}
			
		}
	}
	function editEvent(){


			$id 		 = $_REQUEST['id'];
			$edit_delete = $_REQUEST['edit_delete'];	

			if (($edit_delete)) {
				$sql = "UPDATE events 
						SET bDeleted = 1
						WHERE id = '$id' ";
				Reg::$db->query($sql);
			}

			$title_edit 	= $_REQUEST['edit_title'];
			$color_edit 	= $_REQUEST['edit_color'];
		
			if (!empty($id)) {

				if (!empty($title_edit) || !empty($color_edit)) {

				$sql = "update events 
						set title='$title_edit', color='$color_edit'
						where id='$id' ";
				$res = Reg::$db->query($sql);
				}
		}

	}
	function editEventDate(){

		if (isset($_REQUEST['Event'][0]) && isset($_REQUEST['Event'][1]) && isset($_REQUEST['Event'][2])){
			
			
			$id 	= $_REQUEST['Event'][0];
			$start 	= $_REQUEST['Event'][1];
			$end 	= $_REQUEST['Event'][2];

			$sql 	= "UPDATE events SET  start = '$start', end = '$end' WHERE id = $id ";

			$res 	= Reg::$db->query($sql);
			if ($res) {
				return true;
			}
		}
	}
}
?>