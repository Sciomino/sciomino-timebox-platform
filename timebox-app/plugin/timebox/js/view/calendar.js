<script type="text/javascript">
var html = "";
html = html + "<h2>";
html = html + "<a href='#' data='/Timebox/availability?MCOW-transition=out' class='backbtn white mcow-touchable openinternalpage'>&lt;</a>";
html = html + "Kies een datum";
html = html + "</h2>";
MCOW.Util.setHTML("topbar",html);
document.getElementById("topbar").classList.add("calendarHeader");
</script>
<div id="content">
	<div id="container">
		<div class="calendar" id="CALENDAR_SCRIPT_LIST">
			<script type="text/javascript">
				var html = "";
				for (cal=0;cal<MCOW.Session.Response.param["calendarList"].length;cal++) {
					var CurCal = MCOW.Session.Response.param["calendarList"][cal];

					html = html + "<div>";
					
					// header
					html = html + "<h3>" + CurCal["monthString"] + " " + CurCal["year"] + "</h3>";
					
					// start table
					html = html + "<table>";
					
					// head
					html = html + "<thead><tr><td>ma</td><td>di</td><td>wo</td><td>do</td><td>vr</td><td>za</td><td>zo</td></tr></thead>";

					// body
					html = html + "<tbody>";
					
					var calendar = CurCal["calendar"];
					for (i=0; i<=5; i++) {
						html = html + "<tr>";

						// skip empty lines
						if (! (calendar[i][0] == 0 && calendar[i][6] == 0)) {

							for (j=0; j<=6; j++) {
								if (calendar[i][j] == 0) {
									html = html + "<td class='disabled'></td>";
								}
								else {
									var d = new Date(CurCal["year"], CurCal["month"], calendar[i][j]);
									var unixTime = Math.round(d.getTime()/1000);
									if (calendar[i][j] < CurCal["day"]) {
										html = html + "<td class='disabled'>" + calendar[i][j] + "</td>";
									}
									else if (calendar[i][j] == CurCal["day"]) {
										html = html + "<td><a class='selected' href='#' data='" + unixTime + "'>" + calendar[i][j] + "</a></td>";
									}
									else {
										html = html + "<td><a class='mcow-touchable setavailabilityuntil' href='#' data='" + unixTime + "'>" + calendar[i][j] + "</a></td>";
									}
								}
							}
							
						}
						html = html + "</tr>";
					}

					html = html + "</tbody>";

					// end table
					html = html + "</table>";

					html = html + "</div>";
				}
				MCOW.Util.setHTML("CALENDAR_SCRIPT_LIST",html);
			</script>			
		</div>
	</div>
</div>
