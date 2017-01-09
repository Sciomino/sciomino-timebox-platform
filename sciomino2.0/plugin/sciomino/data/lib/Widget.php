<?php
//
// Get Widget based on WID
//
function WidgetGetWidgetWithWID ($wid) {
        global $XCOW_B;

        $widget = array();

        $result = mysql_query("SELECT AuthWidget.AuthWidgetId, AuthWidget.AuthWidgetWID, AuthWidget.AuthWidgetOwner, AuthWidget.AuthWidgetName, AuthWidget.AuthWidgetKey, AuthWidget.AuthWidgetNetwork, AuthWidget.AuthWidgetLanguage From AuthWidget WHERE AuthWidgetWID = '$wid'", $XCOW_B['mysql_link']);

        if ($result) {
            if (mysql_num_rows($result) ==  1 ) {
				$result_row = mysql_fetch_row($result);
				$widget['id'] = $result_row[0];
 				$widget['wid'] = $result_row[1];
 				$widget['owner'] = $result_row[2];
 				$widget['name'] = $result_row[3];
 				$widget['key'] = $result_row[4];
 				$widget['network'] = $result_row[5];
 				$widget['language'] = $result_row[6];
            }
        }
		else {
			catchMysqlError("WidgetGetWidgetWithWID", $XCOW_B['mysql_link']);
		}

        return $widget;
}

?>
