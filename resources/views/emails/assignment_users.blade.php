<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
 </head>
<body>
    <h1>Assignment Assigned user</h1>

    <pre>Dear {{$user_name}} ,

       Your's Assigned assessment  <b>{{$assignment_name}}</b> by Administrator. 

       Avaliable Test from Starting Date<b> {{$startdatetime}} </b>to Ending Date <b>{{$enddatetime}}</b>.

    <p>	
       <a href='{{ url("/") }}'>Assessment Tool</a>
    </p>

    </pre>
 </body>
</html>