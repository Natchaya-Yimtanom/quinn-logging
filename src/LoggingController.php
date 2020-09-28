<?php

namespace Quinn\Logging;

use App\Http\Controllers\Controller;
use Quinn\Logging\Logging;
use Illuminate\Http\Request;

class LoggingController extends Controller
{
    /**
     * @var BaseLogger
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //show all log data in log viewer
    public function view()
    {
        $month = date('F');
        $monthM = date('m');

        $tables = Logging::where('date', 'like','%-'.$monthM.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();

        $dateLists = Logging::distinct()
                        ->select('date')
                        ->where('date', 'like','%-'.$monthM.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();

        $date = '';
        // return view()->file('..\vendor\ahost\logging\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
        return view()->file('..\packages\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
    }

    //show selected date log data in log viewer
    public function show($date)
    {
        if (strpos($date, "-")) { 
            $startCharCount = strpos($date, "-") + strlen("-");
            $firstSubStr = substr($date, $startCharCount, strlen($date));
            $endCharCount = strpos($firstSubStr, "-");
            if ($endCharCount == 0) {
                $endCharCount = strlen($firstSubStr);
            }
            $month = substr($firstSubStr, 0, $endCharCount);
        }

        $tables = Logging::where('date', 'like', $date)
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();

        $dateLists = Logging::distinct()
                        ->select('date')
                        ->where('date', 'like','%-'.$month.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
                
        $month = date("F", mktime(0, 0, 0, $month, 10));
        // return view()->file('..\vendor\ahost\logging\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
        return view()->file('..\packages\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
    }

    //show selected month log data in log viewer
    public function send( Request $request)
    {
        $month = $request->month;
        $level = $request->level;
       
        $tables = Logging::where('date', 'like','%-'.$month.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
        

        $dateLists = Logging::distinct()
                        ->select('date')
                        ->where('date', 'like','%-'.$month.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();

        $month = date("F", mktime(0, 0, 0, $month, 10));
        $date = '';
        // return view()->file('..\vendor\ahost\logging\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
        return view()->file('..\packages\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
    }

    //show selected level log data in log viewer
    public function level( $month,$level)
    {
        $monthM = date("m", strtotime($month));

        if($month[0] != "2"){
            if($level != 'all'){
                $tables = Logging::where('level_name', 'like', $level)
                        ->where('date', 'like','%-'.$monthM.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
            } else{
                $tables = Logging::where('date', 'like','%-'.$monthM.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
            }
            $date = '';
        } else{
            if($level != 'all'){
                $tables = Logging::where('level_name', 'like', $level)
                        ->where('date', 'like', $month)
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
            } else{
                $tables = Logging::where('date', 'like','%-'.$monthM.'-%')
                        ->where('date', 'like', $month)
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();
            }
            $date = $month;
        }

        $dateLists = Logging::distinct()
                        ->select('date')
                        ->where('date', 'like','%-'.$monthM.'-%')
                        ->orderBy('date', 'desc')
                        ->orderBy('time', 'desc')
                        ->get();

        $month = date("F", mktime(0, 0, 0, $monthM, 10));

        // return view()->file('..\vendor\ahost\logging\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
        return view()->file('..\packages\resources\views\LoggingViewer.blade.php', ['dateLists' => $dateLists , 'tables' => $tables, 'month' => $month , 'date' => $date]);
    }

}