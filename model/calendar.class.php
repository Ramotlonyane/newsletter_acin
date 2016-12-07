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

		if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){

			if (!empty($_POST['title']) && !empty($_POST['start']) && !empty($_POST['end']) && !empty($_POST['color'])) {

				$title 	= $_POST['title'];
				$start 	= $_POST['start'];
				$end 	= $_POST['end'];
				$color 	= $_POST['color'];

				$sql = "INSERT INTO events(title, start, end, color) values ('$title', '$start', '$end', '$color')";
				
				$res = Reg::$db->queryArray($sql);

				return $res;
			}
			
		}
	}
	function editEvent(){

		if (isset($_POST['edit_delete']) && isset($_POST['id'])){

			$id 		= $_POST['id'];
			$edit_delete = $_POST['edit_delete'];	

			if (!empty($edit_delete)) {
			$sql = "UPDATE events 
					SET bDeleted = 1
					WHERE id = '$id' ";
			Reg::$db->query($sql);}
	
		}elseif (isset($_POST['edit_title']) && isset($_POST['edit_color']) && isset($_POST['id'])){

				$id 			= $_POST['id'];
				$title_edit 	= $_POST['edit_title'];
				$color_edit 	= $_POST['edit_color'];
			
				if (!empty($id)) {

					if (!empty($title_edit) || !empty($color_edit)) {

					$sql = "update events 
							set title='$title_edit', color='$color_edit', 
							where id='$id' ";
					$res = Reg::$db->query($sql);
					}
			}

		}
	}
}
?>