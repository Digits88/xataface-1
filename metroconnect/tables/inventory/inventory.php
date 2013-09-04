<?php

class tables_inventory {

	function valuelist__short_code_list() {

                $res = mysql_query("select shortcode,location from shortcode order by shortcode asc");
                $out = array();
                while ( $row = mysql_fetch_assoc($res) ) $out[$row['shortcode']] = $row['shortcode'].' - '.$row['location'];
                return $out;
        }

}
?>

