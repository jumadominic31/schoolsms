<!DOCTYPE html>
<html>
<head>

	<title>Attendance Report</title>
	<style>
	    @page { margin: 100px 25px; font-family:  Helvetica, Arial, sans-serif;}
	    header { position: fixed; top: -60px; left: 0px; right: 0px; background-color: lightblue; height: 60px; }
	    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
	    p { page-break-after: always; }
	    p:last-child { page-break-after: never; }
      table { border-collapse: collapse;  width: 100%; font-size: 12px;}
      table, th, td { border: 1px solid black; }
	  </style>
</head>
<body>
	<header style="text-align: center">{{$sch_details['name']}} <br> {{$sch_details['address']}}, {{$sch_details['town']}} <br> Phone: {{$sch_details['telephone']}}</header>
    <footer style="text-align: right">Powered by QBS - info@quadcorn.co.ke</footer>

    <div style="text-align: center"><h2>Attendance Report</h2></div>
    Print Date:  {{date('d-m-Y', strtotime($curr_date))}}<br><br>
    Form: {{$choices['class']}}<br>
    Stream: {{$choices['stream']}}<br>
    Attendance Date: {{date('d-m-Y', strtotime($choices['att_date']))}}<br>
    Check In/Out: {{$choices['checktype']}}<br>

	@include('attendance.attdata')

</body>
</html>