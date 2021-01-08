<br><br>
@if(count($attendances) > 0)
<?php
    $colcount = count($attendances);
    $i = 1;
?>
<table class="table table-striped" >
    <tr>
        <th></th>
        <th>Student</th>
        <th>Adm No</th> 
        <th>Form</th>
        <th>Time</th> 
        <th>Check In/Out</th>        
    @foreach($attendances as $index => $attendance)
    <tr>
        <td>{{$index + 1}}</td>
        <td>{{$attendance['NAME']}}</td>
        <td>{{$attendance['Admno']}}</td>
        <td>{{$attendance['Class']}} {{$attendance['Stream']}}</td>
        <td>{{$attendance['CHECKTIME']}}</td>
        <td>{{$attendance['CHECKTYPE']}}</td>
    </tr>
    @endforeach
</table>
@else
    <p>No Check in/out To Display</p>
@endif