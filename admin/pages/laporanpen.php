<!-- ga kepake -->

<?php
	if($_SESSION[md5('level')] == 3 || $_SESSION[md5('level')] == 0){
		include "laporan_atasan.php";
	}

?>