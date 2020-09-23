<?php
	function datediff($d1, $d2)
	{  
		$d1 = (is_string($d1) ? strtotime($d1) : $d1);  
		$d2 = (is_string($d2) ? strtotime($d2) : $d2);  
		$diff_secs = abs($d1 - $d2);  
		$base_year = min(date("Y", $d1), date("Y", $d2));  
		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);  
		return array("years" => date("Y", $diff) - $base_year);  
	}  
?>

<?php
function datediff2($d1, $d2){
$d1 = (is_string($d1) ? strtotime($d1) : $d1);
$d2 = (is_string($d2) ? strtotime($d2) : $d2);
$diff_secs = abs($d1 - $d2);
$base_year = min(date("Y", $d1), date("Y", $d2));
$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
return array( "years" => date("Y", $diff) - $base_year,
"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff)
- 1, "months" => date("n", $diff) - 1, "days_total" => floor($diff_secs
/ (3600 * 24)), "days" => date("j", $diff) - 1, "hours_total" =>
floor($diff_secs / 3600), "hours" => date("G", $diff), "minutes_total"
=> floor($diff_secs / 60), "minutes" => (int) date("i", $diff),
"seconds_total" => $diff_secs, "seconds" => (int) date("s", $diff) );
}
?>