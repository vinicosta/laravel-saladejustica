<?php

namespace App\Http\Controllers;

Use DB;
Use App\Http\Controllers\IssueController;
Use App\User;
Use App\Issue;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get issues count in collection
        $issues = DB::select(
            "SELECT COUNT(iss.id) AS count_issues, tit.type_id
            FROM issues iss
            INNER JOIN titles tit ON iss.title_id = tit.id
            INNER JOIN collection col ON iss.id = col.issue_id AND col.user_id = ?
            GROUP BY tit.type_id
            ORDER BY tit.type_id",
            [\Auth::id()]
        );

        // Get pages count daily
        $pages_daily = [];

        $start_day = new \DateTime(date('Y-m-d'));
        $interval = new \DateInterval('P6D');
        $start_day->sub($interval);

        for($i = 1; $i <= 7; $i++){
            $daily = DB::select(
                "SELECT SUM(iss.number_pages) AS pages_sum, DATE_FORMAT(red.readed_date, '%d/%m') AS readed_date
                FROM issues iss
                INNER JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
                WHERE red.readed_date = ?
                GROUP BY red.readed_date
                ORDER BY red.readed_date",
                [\Auth::id(), $start_day->format('Y-m-d')]
            );

            if(!count($daily)){
                $daily = DB::select("SELECT 0 AS pages_sum, '" . $start_day->format('d/m') . "' AS readed_date");
            }

            $pages_daily[] = $daily[0];

            $interval = new \DateInterval('P1D');
            $start_day->add($interval);
        }

        // Get pages count monthly
        $start_day = new \DateTime(date('Y-m-d'));
        $interval = new \DateInterval('P1Y');
        $start_day->sub($interval);

        $pages_monthly = DB::select(
            "SELECT SUM(number_pages) AS pages_sum, readed_date FROM (
                SELECT iss.number_pages, DATE_FORMAT(red.readed_date, '%b') AS readed_date
                FROM issues iss
                INNER JOIN readed red ON iss.id = red.issue_id AND red.user_id = ?
                WHERE red.readed_date BETWEEN ? AND ?
            ) AS pages_monthly
            GROUP BY readed_date
            ORDER BY readed_date DESC",
            [\Auth::id(), $start_day->format('Y-m-d'), date('Y-m-d')]
        );

        // Get comics reading list
        $issue = new IssueController();
        $reading_list = $issue->getReadingList(\Config::get('constants.types.comics'));

        // Get latest issues added in collection
        $collection = Issue::select('issues.*', 'titles.type_id')
            ->join('collection', 'issues.id', '=', 'collection.issue_id')
            ->join('titles', 'issues.title_id', '=', 'titles.id')
            ->where('collection.user_id', '=', \Auth::id())
            ->orderBy('collection.added_date', 'DESC')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'issues' => $issues,
            'pages_daily' => $pages_daily,
            'pages_monthly' => $pages_monthly,
            'reading_list' => $reading_list,
            'collection' => $collection
        ]);
    }

    // public function resetPassword(){
    //     $user = User::find(1);
    //     echo '[' . $user->password . ']<br>';
    //     echo '[' . bcrypt('') . ']';
    //     $user->password = bcrypt('');
    //     $user->save();

    //     exit;
    // }
}
