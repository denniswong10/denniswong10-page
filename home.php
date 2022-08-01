<?php
if (file_exists('offline.xml') == false) {
    // Create connection
    $conn = new mysqli("localhost", "id9039118_denniswong10", "pineapple1122", "id9039118_passport_id");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Checking for web_stats
    $sql_web = "SELECT Sites, Remark FROM webpage_controls";
    $result_web = $conn->query($sql_web);

    if ($result_web->num_rows > 0) {
        while ($row_web = $result_web->fetch_assoc()) {
            if ($row_web['Sites'] == 'main_webpage') {
                break;
            }
        }
    
        if ($row_web['Remark'] == 'OPEN') {
		    $siteSTATS = "ONLINE";
		    $action_txt = "meta/connection.php";
        }
        else if ($row_web['Remark'] == 'MAINTENANCE') {
		    $siteSTATS = "MAINTENANCE";
	    }
	    else if ($row_web['Remark'] == 'OFFLINE') {
		    $siteSTATS = "OFFLINE";
		    $action_txt = "";
		}
		else if ($row_web['Remark'] == 'CLOSED') {
		    $siteSTATS = "CLOSED";
		    $action_txt = "";
        }
    }
    
    // Checking for web_admin
    $sql_admin = "SELECT ID FROM web_admin";
    $result_admin = $conn->query($sql_admin);

    if ($result_admin->num_rows > 0) {
        $row_admin = $result_admin->fetch_assoc();
        $print_web_admin = $row_admin['ID'];
    }
}
else {
	$xml_offline = simplexml_load_file('offline.xml');
    $siteSTATS = $xml_offline->siteStats;
	$print_web_admin = $xml_offline->webOwner;
}

// retrieve data to site
$xml_loadData = simplexml_load_file('meta/main.xml');
$xml_serverLog = simplexml_load_file('meta/serverlog.xml');

$count_ServerLogs = count($xml_serverLog->logs);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home Page</title>
	<link rel="stylesheet" type="text/css" href="database/style/style.css">
	<link href="database/images/profile_icon.png" rel="shortcut icon">
	<script src="database/scripts/contentID.js"></script> <!--  Javascript "contentID.js"  -->
	<script src="database/scripts/program.js"></script> <!--   Javascript "program.js"  --> 
	<script type="text/javascript">
	    // Loading of webpage
        function check_webpage_system() {
            if ('<?php echo $siteSTATS ?>' == 'ONLINE' || '<?php echo $siteSTATS ?>' == 'OFFLINE') {
				/*if ('<?php echo $siteSTATS ?>' == 'OFFLINE') {
					alert("[NO NETWORK ACCESS] PASSPORT Verification currently unavailable.");
				}*/
								
                load_visitor_system2 ();
                preload_index();
			}
			else if ('<?php echo $siteSTATS ?>' == 'CLOSED') {
				window.location.assign("meta/maintenance3.html");
			}
            else {
                window.location.assign("meta/maintenance2.html");
            }
		}

		// Logout Function
		function logout_func () {
			localStorage.setItem("passportID", 0);
			window.location.assign("meta/connection1b.php");
		}
	</script>
</head>
<body onload="check_webpage_system();">
	<div class="header">
		<table align="right">
			<tr height="75">
				<td align="left" width="100"><img id="profile_image"></td>
				<th class="contentID" align="left" width="400">
					<p><b>Website Owner:</b> <?php echo $print_web_admin ?></p>
					<p><b>Website Status:</b> <?php echo $siteSTATS ?></p>
				</th>
				<td width="200">
				</td>
				<th class="home-button" width="200" onclick="homebutton_click();">HOME</th>
				<th class="about-button" width="200" onclick="aboutbutton_click();">ABOUT</th>
				<th class="timeline-button" width="200" onclick="timelinebutton_click();">COMMUNITY</th>
				<th class="moresite-button" width="200" onclick="moresitebutton_click();">MORE SITE</th>
			</tr>

			<tr class="loginSTATS_style" id="login_stats"></tr>
		</table>
	</div>

	<div class="info-board">
	    <table class="envet_table_row">
	    	<tr>
	    		<td width="610" align="center" colspan="7"><span style="font-size:30px"><b>Server Notifications</b></span></td>
	    	</tr>

			<tr>
				<td class="text_writeEvent" width="510" height="150" align="center" colspan="6">
					<p>
						<?php							
						    for($i = $count_ServerLogs - 1; $i > $count_ServerLogs - 7; $i--) {
								echo '<p><b>[' .$xml_serverLog->logs[$i]->TypeID .']</b>';
								echo ' ' .$xml_serverLog->logs[$i]->Comments .'</p>';
							}
						?>	
					</p>
				</td>
				<td class="text_writeEvent" width="100" align="left">
					<p>
						<?php					
						    for($i = $count_ServerLogs - 1; $i > $count_ServerLogs - 7; $i--) {
								echo '<p>' .$xml_serverLog->logs[$i]->DateID .'</p>';
							}
						?>
					</p>
				</td>
			</tr>

			<tr>
				<td width="70"></td>
				<td width="82" height="5" align="center" id="index_scroll_log1"></td>
				<td width="82" height="5" align="center" id="index_scroll_log2"></td>
				<td width="82" height="5" align="center" id="index_scroll_log3"></td>
				<td width="82" height="5" align="center" id="index_scroll_log4"></td>
				<td width="82" height="5" align="center" id="index_scroll_log5"></td>
				<td></td>
			</tr>
		</table><br />

		<table class="login_system_table"><form action="<?php echo $action_txt?>" method="POST">
			<tr>
				<td width="610" align="center" colspan="2"><span style="font-size:30px"><b>Passport ID Verification</b></span></td>
			</tr>

			<tr>
				<td width="200" height="50" align="right"><label style="font-weight:bold">* USER : </label></td>
				<td width="410"><input class="input_passportID" type="text" id="passport_id" name="passport_id" autocomplete='off' required></td>
			</tr>

			<tr>
				<td width="200" height="50" align="right"><label style="font-weight:bold">* PASS : </label></td>
				<td width="410"><input class="input_pin" type="password" id="pin" name="pin" required></td>
			</tr>

			<tr>
				<td height="50" align="center" colspan="2">* Contact Website Owner, If you have trouble login.</td>
			</tr>

			<tr>
				<td height="50" align="center" colspan="2">
					<button class="login_system_button" id="login" value="login">LOGIN</button>
				</td>
			</tr>
        </form></table>
	</div>

	<div class="footer">
		<table>
			<tr>
				<td width="1500" height="45" align="center">
					<span style="font-weight:bold">Build by <?php echo $print_web_admin ?> </span> | <span style="font-weight:bold" id="visitor_id">Visitor ID: </span>
			    </td>
			</tr>
		</table>
	</div>
</body>
</html>