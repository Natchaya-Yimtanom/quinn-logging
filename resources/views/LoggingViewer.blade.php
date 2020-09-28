<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Log Viewer</title>
    <link rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

    <style>
        a{
            text-decoration: none;
            color: black;
        }

        a:hover{
            text-decoration: none;
            color: 	#696969;
        }

        select{
            width: 100%;
            padding: 3px;
            margin-bottom: 20px;
        }

        .levelCell{ width: 80px; }
        .dateCell{ width: 110px; }
        
        .rowColor{ 
            background-color: #A9A9A9; 
            min-height: 100vh;
        }

        .status[data-status="ERROR"]{
            background-color: #DC143C;
            color : white;
            text-align: center;
        }

        .status[data-status="INFO"]{
            background-color: #32CD32;
            color : white;
            text-align: center;
        }

        .status[data-status="WARNING"]{
            background-color: #FFD700;
            color : black;
            text-align: center;
        }

        .status[data-status="DEBUG"]{
            background-color: #00008B;
            color : white;
            text-align: center;
        }

        .status[data-status="EMERGENCY"]{
            background-color: #FF8C00;
            color : white;
            text-align: center;
        }

        .status[data-status="NOTICE"]{
            background-color: #1E90FF;
            color : white;
            text-align: center;
        }

        .status[data-status="CRITICAL"]{
            background-color: #8B0000;
            color : white;
            text-align: center;
        }

        .status[data-status="ALERT"]{
            background-color: #8B008B;
            color : white;
            text-align: center;
        }

        .seeAll{
            border: none;
            background-color: transparent;
            color: grey;
            font-size: 12px;
            cursor: pointer;
        }

        .activeDate{
            background-color: #DCDCDC;
        }

        .selectDate{
            width: 100%;
            text-align: center;
            cursor: pointer;
        }

        #stack{
            display: none;
            white-space: pre-line;
        }

        #see:focus + div#stack{
            display: block;
        }

        #levelButton{
            padding: 10px;
            border: none;
        }

        .dropbtn {
            padding: 16px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        #levelList{
            float:right; 
            width: 100%;
            margin: 15px 15px 0px 0px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content input {
            color: black;
            padding: 8px;
            width: 133px;
            text-decoration: none;
            display: block;
            cursor: pointer;
        }
        
        .dropdown-content input:hover {background-color: #f1f1f1}
        .dropdown:hover .dropdown-content {display: block;}
        .dropdown:hover .dropbtn {background-color: #DCDCDC;}

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col sidebar rowColor">
                <h1 style="margin-bottom: 30px; margin-top: 10px; text-align: center;">
                    <a href="{{route('view')}}">Log Viewer</a>
                </h1>

                <h6>Select month</h6>
                <form method="post" id="selectMonth" action="{{route('send')}}">
                    <select id="month"  name="month" onchange="document.getElementById('selectMonth').submit();">
                        <option value="">{{$month}}</option>
                        <option value="01">January</option>
                        <option value="02">Febuary</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </form>

                <h3 id="showMonth" style="text-align: center; margin: 10px 0px 20px 0px;">{{$month}}</h3>

                @if($dateLists->isEmpty())
                <p style="text-align: center;">There is no log in {{$month}}</p>
                @else
                    @foreach ($dateLists as $dateList)
                        @if($dateList->date == $date)
                            <input type="button" value="{{ $dateList->date }}" class="list-group-item selectDate activeDate "
                                onclick="window.location.href='{{route('show', ['date' => $dateList->date])}}'">
                        @else
                            <input type="button" value="{{ $dateList->date }}" class="list-group-item selectDate"
                                onclick="window.location.href='{{route('show', ['date' => $dateList->date])}}'">
                        @endif
                    @endforeach
                @endif

            </div>

            <div class="col-10 sidebar mb-3">
                @if($date != null)
                    <h3 style="margin: 20px 0px 20px 0px; display:inline-block;">Log data in : {{$date}}</h3>
                @else
                    <h3 style="margin: 20px 0px 20px 0px; display:inline-block;">Log data in : {{$month}}</h3>
                @endif
                <div class="dropdown" id="levelList" style="width:auto">
                    <button class="dropbtn" id="levelButton">Select Log Level</button>
                    <div class="dropdown-content" style="left:0">
                        @if($date == null)
                        <input value="All Level" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'all'])}}'">
                        <input value="INFO" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'INFO'])}}'">
                        <input value="ERROR" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'ERROR'])}}'">
                        <input value="ALERT" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'ALERT'])}}'">
                        <input value="EMERGENCY" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'EMERGENCY'])}}'">
                        <input value="CRITICAL" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'CRITICAL'])}}'">
                        <input value="WARNING" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'WARNING'])}}'">
                        <input value="NOTICE" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'NOTICE'])}}'">
                        <input value="DEBUG" onclick="window.location.href='{{route('level',['month' => $month,'level' => 'DEBUG'])}}'">
                        @else
                        <input value="All Level" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'all'])}}'">
                        <input value="INFO" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'INFO'])}}'">
                        <input value="ERROR" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'ERROR'])}}'">
                        <input value="ALERT" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'ALERT'])}}'">
                        <input value="EMERGENCY" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'EMERGENCY'])}}'">
                        <input value="CRITICAL" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'CRITICAL'])}}'">
                        <input value="WARNING" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'WARNING'])}}'">
                        <input value="NOTICE" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'NOTICE'])}}'">
                        <input value="DEBUG" onclick="window.location.href='{{route('level',['month' => $date,'level' => 'DEBUG'])}}'">
                        @endif
                    </div>
                </div>
                    
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="levelCell" style="text-align: center">Level</th>
                            <th class="dateCell" style="text-align: center">User</th>
                            <th class="dateCell" style="text-align: center">Date-Time</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($tables->isEmpty())
                    <tr>
                        <td colspan="4" style="text-align: center">No log data available</td>
                    </tr>
                    @else
                        @foreach ($tables as $table)
                        <tr>
                            <td data-status="{{$table->level_name}}" class="status">{{ $table->level_name }}</td>
                            <td style="text-align: center">{{ $table->user }}</td>
                            <td>{{ $table->date }} {{ $table->time }}</td>
                            
                            @if($table->stack != null)
                            <td id="message">{{ $table->message }} 
                                <button id="see" class="seeAll">See All</button>
                                <div id="stack">
                                    {{ $table->stack }}
                                </div>
                            </td>
                            @else
                            <td>{{ $table->message }} </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</body>
</html>