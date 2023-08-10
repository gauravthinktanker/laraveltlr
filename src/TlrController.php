<?php

namespace Laraveltlr\Tlr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, DB, Session, DateTimeZone, DateTime;
use App\Models\Role;
use App\Models\EmployeeDetails;
use App\Models\User;
use App\Helper\Reply;
use App\Traits\UniversalSearchTrait;

class TlrController extends Controller
{
use UniversalSearchTrait;

    public function index(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-month";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR Month";

        if (Session::get('month') != 15) {
            $month = Session::get('month') + 1;
        } else if (isset($request->month)) {
            $month = intval($request['month']) + 1;
        } else {
            $month = date('m');
        }

        if (isset($request->year)) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }

        $id = $id;
        $logged_user_id = $id;

        if ($request->ajax()) {
            $where_str = "1 =?";
            $where_param = array('1');

            if ($request->has('search.value')) {

                $search = $request->search['value'];

                $where_str .= " and (subject like \"%{$search}%\""
                    . " or topic.topic like \"%{$search}%\""
                    . ")";
            }

            $columns = array('point.topic_id', 'point.subject', 'point.from_hour', 'point.to_hour', 'point.participant', 'point.point', 'point.date', 'topic.topic');



            $Point = DB::table("point")
                ->selectRaw("DATE_FORMAT(point.date, '%d/%m/%Y') as date,topic.topic,point.point,point.topic_id,point.from_hour,point.to_hour,point.participant,point.subject")
                ->leftjoin('topic', 'point.topic_id', '=', 'topic.id')
                ->whereRaw('MONTH(date) = ?', [$month])
                ->whereRaw('YEAR(date) = ?', [$year])
                ->where('user_id', $id)
                ->whereRaw($where_str, $where_param);
            $point_count = $Point->count();

            if ($request->has('start') && $request->get('length') != '-1') {
                $Point = $Point->take($request->get('length'))
                    ->skip($request->get('start'));
            }

            if ($request->has('order')) {
                $sql_order = '';
                for ($i = 0; $i < $request->input('order.0.column'); $i++) {
                    $column = $columns[$i];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Point = $Point->orderBy($column, $request->input('order.' . $i . '.dir'));
                }
            }
            $Point = $Point->get()->toArray();


            $Point = json_decode(json_encode($Point), true);

            // dd($totalpoint[0]['totalpoint']);
            foreach ($Point as $key => $value) {

                $topic = DB::table('topic')->select('topic', 'id')->where('id', $value['topic_id'])->get()->toArray();
                $topics = array_column($topic, 'topic');
                $Point[$key]['topic_id'] = $topics;
            }
            // dd($Point);
            $response['iTotalDisplayRecords'] = $point_count;
            $response['iTotalRecords'] = $point_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $Point;
            return $response;
        }

        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';


            //dd($all_users);
        }

        //  return view('point.userviewindex',['id'=>$id,'logged_user_id'=>$logged_user_id]);
        return view('tlr::tlr_month', ['id' => $id, 'logged_user_id' => $logged_user_id], $this->data);
    }
    public function tlrAdminMonth(Request $request)
    {

        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-point";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR Point";

        if (Session::get('month') != 15) {
            $month = Session::get('month') + 1;
            $user_id = Session::get('user_id');
        } else if (isset($request->month)) {
            $month = intval($request['month']) + 1;
            $user_id = $request->user;
        } else {
            $user_id = $request->user;
            $month = date('m');
        }

        if (isset($request->year)) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }
        if ($request->ajax()) {
            $where_str = "1 =?";
            $where_param = array('1');

            if ($request->has('search.value')) {

                $search = $request->search['value'];

                $where_str .= " and (users.name like \"%{$search}%\""
                    . " or topic_id like \"%{$search}%\""
                    . ")";
            }
            $columns = array('point.id', 'point.user_id', 'point.from_hour', 'point.to_hour', 'point.topic_id', 'point.subject', 'point.participant', 'point.point', 'point.date', 'users.name');
            if ($user_id != null) {

                $point_count = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,users.name")
                    ->join('users', 'point.user_id', '=', 'users.id')
                    ->where('user_id', $user_id)
                    ->whereRaw('MONTH(date) = ?', [$month])
                    ->whereRaw('YEAR(date) = ?', [$year])
                    ->whereRaw($where_str, $where_param)
                    ->where('users.status', 'active')
                    ->count();

                $Point = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(point.date, '%d/%m/%Y') as date,users.name,point.id as id")
                    ->join('users', 'point.user_id', '=', 'users.id')
                    ->where('user_id', $user_id)
                    ->whereRaw('MONTH(date) = ?', [$month])
                    ->whereRaw('YEAR(date) = ?', [$year])
                    ->where('users.status', 'active')
                    ->whereRaw($where_str, $where_param);
            } else {

                $point_count = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,users.name")
                    ->join('users', 'point.user_id', '=', 'users.id')
                    ->whereRaw('MONTH(date) = ?', [$month])
                    ->whereRaw('YEAR(date) = ?', [$year])
                    ->whereRaw($where_str, $where_param)
                    ->where('users.status', 'active')
                    ->count();

                $Point = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(point.date, '%d/%m/%Y') as date,users.name,point.id as id")
                    ->join('users', 'point.user_id', '=', 'users.id')
                    ->whereRaw('MONTH(date) = ?', [$month])
                    ->whereRaw('YEAR(date) = ?', [$year])
                    ->where('users.status', 'active')
                    ->whereRaw($where_str, $where_param);
            }

            if ($request->has('start') && $request->get('length') != '-1') {
                $Point = $Point->take($request->get('length'))
                    ->skip($request->get('start'));
            }

            if ($request->has('order')) {
                $sql_order = '';
                for ($i = 0; $i < $request->input('order.0.column'); $i++) {
                    $column = $columns[$i];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Point = $Point->orderBy($column, $request->input('order.' . $i . '.dir'));
                }
            }
            $Point = $Point->get()->toArray();
            $array_data = json_decode(json_encode($Point), true);

            foreach ($array_data as $key => $value) {
                $team_member = DB::table("users")->select('name', 'id')->where('id', $value['user_id'])->where('status', 'active')->get()->toArray();

                $topic =  DB::table("topic")->select('topic', 'id')->where('id', $value['topic_id'])->get()->toArray();

                $users = array_column($team_member, 'name');
                $topics = array_column($topic, 'topic');
                $array_data[$key]['user_id'] = $users;
                $array_data[$key]['topic_id'] = $topics;
            }

            $response['iTotalDisplayRecords'] = $point_count;
            $response['iTotalRecords'] = $point_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $array_data;

            return $response;
        }


        $all_users = [];
        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';


            $all_users = DB::table('users')->where('status', 'active')->orderBy('name')->pluck('name', 'id')->toArray();
            //dd($all_users);
        }
        return view('tlr::tlr_month_admin', ['all_users' => $all_users], $this->data);
    }
    public function tlrYear(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-year";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR Year";

        if (isset($request->year)) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }


        if ($user->roles[0]['name'] == 'admin') {
            $user_id = $request->user;
        } else {
            $user_id = Auth::id();
        }

        Session::put("user_name", $user_id);
        Session::put("user_id", $user_id);

        $logged_user_id = $user_id;

        if ($request->ajax()) {
            $where_str = "1 =?";
            $where_param = array('1');

            $columns = array('id', 'date', 'point');

            if (!empty($request->from_date)) {

                $Point = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,SUM(point) as point,DATE_FORMAT(date, '%m') as month")
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->where('user_id', $user_id)
                    ->orderBy('month', 'ASC')
                    ->groupBy('date')
                    ->whereRaw($where_str, $where_param);
            } else {

                $Point = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,SUM(point) as point,DATE_FORMAT(date, '%m') as month")
                    ->whereRaw('YEAR(date) = ?', [$year])
                    ->where('user_id', $user_id)
                    ->orderBy('month', 'ASC')
                    ->groupBy('date')
                    ->whereRaw($where_str, $where_param);
            }
            // dd($Point->get()->toArray());

            // dd($Point->get()->toArray());s

            if ($request->has('start') && $request->get('length') != '-1') {
                $Point = $Point->take($request->get('length'))
                    ->skip($request->get('start'));
            }

            if ($request->has('order')) {
                $sql_order = '';
                for ($i = 0; $i < $request->input('order.0.column'); $i++) {
                    $column = $columns[$i];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $Point = $Point->orderBy($column, $request->input('order.' . $i . '.dir'));
                }
            }
            $Point = $Point->get()->toArray();

            $array_data = json_decode(json_encode($Point), true);
            $carry = [];

            foreach ($array_data as $key => $value) {
                $date = strtr($value['date'], '/', '-');
                $month = date('m', strtotime($date));
                $sku  = $month;
                // dd($sku);
                if (array_key_exists($month, $carry)) {

                    $carry[$sku]['point'] += $value['point'];
                } else {

                    $carry[$sku]['id'] = $value['id'];
                    $carry[$sku]['date'] = $value['date'];
                    $carry[$sku]['point'] = $value['point'];
                }
            }

            $carry = array_values(json_decode(json_encode($carry), true));
            // dd(array_values($carry));

            $response['iTotalDisplayRecords'] = count($carry);
            $response['iTotalRecords'] = count($carry);

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $carry;
            //dd($response);
            return $response;
        }

        $all_users = [];
        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';


            $all_users = DB::table('users')->where('status', 'active')->orderBy('name')->pluck('name', 'id')->toArray();
            //dd($all_users);
        }

        return view('tlr::tlr_year', ['all_users' => $all_users, 'id' => $user_id, 'logged_user_id' => $logged_user_id], $this->data);
    }

    public function tlrCreate(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-create";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR-Point";

        if ($request->ajax()) {
            $id = $request->id;
            $participant = DB::table('users')->select('name', 'id')->where('status', 'active')->get()->pluck('name', 'id')->toArray();
            unset($participant[$id]);
            return response()->json(["participant" => $participant]);
        }

        $topic =  DB::table("topic")->select('topic', 'id')->get()->pluck('topic', 'id')->toArray();
        unset($topic[10]);
        unset($topic[11]);

        $users = DB::table('users')->select('name', 'id')->where('status', 'active')->get()->pluck('name', 'id')->toArray();


        $skills = ['Unsatisfactory', 'Less than satisfactory', 'Fully satisfactory', 'Excellent'];
        return view('tlr::tlr_create', ['topic' => $topic, 'users' => $users, 'skills' => $skills], $this->data);
    }

    public function tlrStore(Request $request)
    {

        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-store";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR Store";

        $rules = [
            'topic' => 'required',
            'date' => 'required',
            'organizer' => 'required'
        ];

        $this->validate($request, $rules);

        $data = $request->skill;
        $topic_point = DB::table('topic')->select('point')->where('id', $request->topic)->get()->pluck('point')->toArray();

        if ($data == 1) {
            $point_score = 1;
            $request['subject'] = "Less than satisfactory";
        } elseif ($data == 2) {
            $point_score = 3;
            $request['subject'] = "Fully satisfactory";
        } elseif ($data == 3) {
            $point_score = 5;
            $request['subject'] = "Excellent";
        } else {
            $point_score = implode(',', $topic_point);
        }
        $topic_id = $request->topic;
        $user_id = $request->organizer;
        $date = date('Y-m-d', strtotime($request->date));
        $subject = $request->subject;

        if ($request->from_hour != "10:00 AM" && $request->to_hour != "07:00 PM") {
            $from_hour = date('h:i A', strtotime($request->from_hour));
            $to_hour = date('h:i A', strtotime($request->to_hour));
        } else {
            $from_hour = "";
            $to_hour = "";
        }
        DB::table('point')->updateOrInsert([
            'point' => $point_score,
            'topic_id' => $topic_id,
            'user_id' => $user_id,
            'date' => $date,
            'subject' => $subject,
            'from_hour' => $from_hour,
            'to_hour' => $to_hour,
        ]);

        $participant = $request->participant;

        if ($participant != null) {
            foreach ($participant as $value) {
                $topic_id = $request->topic;
                $participant = "1";
                $user_id = $value;
                $subject = $request->subject;
                if ($request->from_hour != "10:00 AM" && $request->to_hour != "07:00 PM") {
                    $from_hour = date('h:i A', strtotime($request->from_hour));
                    $to_hour = date('h:i A', strtotime($request->to_hour));
                } else {
                    $from_hour = "";
                    $to_hour = "";
                }
                $date = date('Y-m-d', strtotime($request->date));
                $point_score = 5;

                DB::table('point')->updateOrInsert([
                    'point' => $point_score,
                    'topic_id' => $topic_id,
                    'user_id' => $user_id,
                    'date' => $date,
                    'subject' => $subject,
                    'from_hour' => $from_hour,
                    'to_hour' => $to_hour,
                ]);
            }
        }

        return redirect()->route('tlr_month_admin')
            ->with('message', 'Points Added Successfully.')
            ->with('message_type', 'success');
    }

    public function tlrAdminMonthDelete(Request $request)
    {
        DB::table("point")->where('id', $request->id)->delete();

        return back()->with('message', 'Point deleted successfully')
            ->with('message', 'success');
    }

    public function monthset($month)
    {
        Session::put("month", $month);
        return redirect()->route('tlr_month_admin');
    }

    public function monthsetuser($month_user)
    {

        Session::put("month", $month_user);
        return redirect()->route('tlr_month');
    }

    public function masterindex(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-point-master";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR Point Master";

        if (isset($request->month)) {
            $month = intval($request['month']) + 1;
        } else {

            $month = date('m');
        }
        if (isset($request->year)) {
            $year = $request->year;
        } else {

            $year = date('Y');
        }

        $id = Auth::id();
        $logged_user_id = Auth::id();
        if ($request->ajax()) {
            $where_str = "1 =?";
            $where_param = array('1');

            if ($request->has('search.value')) {
                $search = $request->search['value'];
                $where_str .= " and (users.name like \"%{$search}%\""
                    . " or point.point like \"%{$search}%\""
                    . ")";
            }

            $columns = array('users.name', 'point.point', 'point.id', 'point.user_id', 'point.from_hour', 'point.to_hour', 'point.topic_id', 'point.subject', 'point.participant', 'point.date');

            $Point = DB::table("point")
                ->selectRaw("*,DATE_FORMAT(point.date, '%d/%m/%Y') as date,SUM(point.point) as point,users.name")
                ->join('users', 'point.user_id', '=', 'users.id')
                ->whereRaw('MONTH(date) = ?', [$month])
                ->whereRaw('YEAR(date) = ?', [$year])
                ->where('users.status', 'active')
                ->groupby('user_id')
                ->whereRaw($where_str, $where_param);

            $Point_count = $Point->get()->toArray();

            if ($request->has('start') && $request->get('length') != '-1') {
                $Point = $Point->take($request->get('length'))
                    ->skip($request->get('start'));
            }

            if ($request->has('order')) {
                $sql_order = '';
                $column = $columns[$request->input('order.0.column')];
                if ($column == 'point.point') {
                    $Point = $Point->orderBy('point', $request->input('order.0.dir'));
                } else {
                    $Point = $Point->orderBy($column, $request->input('order.0.dir'));
                }
            }
            $Point = $Point->get()->toArray();
            $response['iTotalDisplayRecords'] = count($Point_count);
            $response['iTotalRecords'] = count($Point_count);

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $Point;

            return $response;
        }

        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';
        }
        return view('tlr::tlr_point', ['id' => $id, 'logged_user_id' => $logged_user_id], $this->data);
    }

    public function userset($month, $user_id)
    {
        Session::put("month", $month);
        Session::put("user_id", $user_id);
        return redirect()->route('tlr_month_admin');
    }

    public function topicindex(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-headers";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "TLR Headers";

        if ($request->ajax()) {
            $where_str = '1 = ?';
            $where_params = array('1');

            if ($request->get('search')['value'] != "") {
                $search = $request->get('search')['value'];
                $where_str .= " and ( topic like \"%{$search}%\""

                    . ")";
            }
            $user = DB::table("topic")
                ->select('id', 'topic', 'point')
                ->whereRaw($where_str, $where_params);

            $user_count =  DB::table("topic")
                ->whereRaw($where_str, $where_params)
                ->count();

            $columns = ['id', 'topic', 'point'];
            $order_columns = ['topic'];

            if ($request->has('start') && $request->get('length') != '-1') {
                $user = $user->take($request->get('length'))
                    ->skip($request->get('start'));
            }
            if ($request->has('order')) {
                $sql_order = '';
                for ($i = 0; $i <= $request->input('order.0.column'); $i++) {
                    $column = $order_columns[$i];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $user = $user->orderBy($column, $request->input('order.' . $i . '.dir'));
                }
            }


            $user = $user->get();
            $response['iTotalDisplayRecords'] = $user_count;
            $response['iTotalRecords'] = $user_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $user;

            return $response;
        }

        $totalpoint = DB::table("topic")
            ->selectRaw("SUM(point) as point")->pluck('point')->toArray();

        // dd($totalpoint[0]);


        return view('tlr::tlr_topic', ['totalpoint' => $totalpoint], $this->data);
    }


    public function topiccreate(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-create";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "Add Headers";

        return view('tlr::topic_create', $this->data);
    }

    public function topicstore(Request $request)
    {
        $this->validate($request, [
            'topic' => 'required',
            'point' => 'required',
        ]);

        DB::table('topic')->updateOrInsert([
            "topic" =>  $request->topic,
            "point" =>  $request->point,
        ]);

        return redirect()->route('topic.index')
            ->with('message', 'Topic Added Successfully.')
            ->with('message_type', 'success');
    }

    public function topicedit($rowid)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "tlr-headers";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "Edit Headers";


        $topic = DB::table('topic')->select('id', 'topic', 'point')->where('id', $rowid)->first();
        return view('tlr::topic_edit', compact('topic'), $this->data);
    }

    public function topicupdate(Request $request, $id)
    {

        $this->validate($request, [
            'topic' => 'required',
            'point' => 'required',
        ]);

        //  $topic = Topic::find($id);
        //  $topic->topic = $request->topic;
        //  $topic->point = $request->point;

        //  $topic->save();
        DB::table('topic')->updateOrInsert(['id' => $id], [
            "topic" =>  $request->topic,
            "point" =>  $request->point,
        ]);

        return redirect()->route('topic.index')
            ->with('message', 'Record Updated successfully')
            ->with('message_type', 'success');
    }

    public function topicdelete(Request $request)
    {
        DB::table('topic')->where('id', $request->id)->delete();

        return back()->with('message', 'Project deleted successfully')
            ->with('message', 'success');
    }

    public function services(Request $request)
    {

        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "services";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "Services";

        if ($request->ajax()) {

            $where_str = '1 = ?';
            $where_params = [1];

            if ($request->has('sSearch')) {
                $search = $request->get('sSearch');
                $where_str .= " and (services.phone_num like \"%{$search}%\""
                    . " or services.person_name like \"%{$search}%\""
                    . " or category.category_name like \"%{$search}%\""
                    . ")";
            }
            $columns = ['services.id', 'services.person_name as person_name', 'services.phone_num as phone_num', 'category.category_name as category'];

            $services_count = DB::table('services')->select($columns)
                ->leftjoin('category', 'category.id', '=', 'services.category')
                ->whereRaw($where_str, $where_params)
                ->count();
            $services = DB::table('services')->select($columns)
                ->leftjoin('category', 'category.id', '=', 'services.category')
                ->whereRaw($where_str, $where_params);

            if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
                $services = $services->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
            }

            if ($request->has('iSortCol_0')) {
                for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
                    $column = $columns[$request->get('iSortCol_' . $i)];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $services = $services->orderBy($column, $request->get('sSortDir_' . $i));
                }
            }

            $services = $services->get();
            $response['iTotalDisplayRecords'] = $services_count;
            $response['iTotalRecords'] = $services_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $services;

            return $response;
        }

        return view('tlr::services', $this->data);
    }
    public function servicesCreate()
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "services_create";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "Services-Create";

        $category = ['NEW' => "Add New Category"] + DB::table('category')->orderBy('category_name', 'asc')->pluck('category_name', 'id')->toArray();
        return view('tlr::services_create', ['category' => $category], $this->data);
    }

    public function servicesStore(Request $request)
    {
        $this->validate($request, [

            'person_name' => 'required',
            'category' => 'required',
        ], [
            'person_name.required' => 'Person Name is Required',
            'category.required' => 'Category is Required',
        ]);

        $services_master = $request->all();

        $phone_num = implode(',', $services_master['phone_num']);
        DB::table('services')->updateOrInsert([
            "person_name" =>  $request->person_name,
            "category" =>  $request->category,
            "phone_num" =>  $phone_num,
        ]);

        if ($services_master['save_button'] == "save") {
            return redirect()->back()
                ->with('message', 'Record Added successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('services.index')
            ->with('message', 'Record Added successfully')
            ->with('message_type', 'success');
    }
    public function servicesEdit($rowid)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "services-edit";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "Services-Edit";

        $category = ['NEW' => "Add New Category"] + DB::table('category')->orderBy('category_name', 'asc')->pluck('category_name', 'id')->toArray();
        $service_master = DB::table('services')->where('id', $rowid)->first();
        //$service_master = json_encode($service_master);
        //$service_master = json_decode($service_master,true);

        //dd($service_master);
        return view('tlr::services_edit', ['service_master' => $service_master, 'category' => $category], $this->data);
    }
    public function servicesUpadate(Request $request, $rowid)
    {
        $this->validate($request, [

            'person_name' => 'required',
            'category' => 'required',
        ], [
            'person_name.required' => 'Person Name is Required',
            'category.required' => 'Category is Required',
        ]);
        $services_master = $request->all();
        $phone_num = implode(',', $services_master['phone_num']);
        DB::table('services')->updateOrInsert(['id' => $rowid], [
            "person_name" =>  $request->person_name,
            "category" =>  $request->category,
            "phone_num" =>  $phone_num,
        ]);
        if ($services_master['save_button'] == "save") {
            return redirect()->back()
                ->with('message', 'Record Updated successfully')
                ->with('message_type', 'success');
        }
        return redirect()->route('services.index')
            ->with('message', 'Record Updated successfully')
            ->with('message_type', 'success');
    }
    public function servicesDelete(Request $request)
    {
        $id = $request->get('id');

        DB::table('services')->where('id', $id)->delete();

        return back()->with('message', 'Record Deleted Successfully.')
            ->with('message_type', 'success');
    }

    public function categoryStore(Request $request)
    {
        $category_data = $request->all();
        $cat_value = $category_data['newval'];

        $cat_id = DB::table('category')->insertGetId([
            "category_name" =>  $cat_value
        ]);

        return $cat_id;
    }

    public function timelog($employee_id)
    {
        $data_user = DB::table('employee_details')->select('user_id')->where('employee_id',$employee_id)->first();
        $user_id =  $data_user->user_id;
        $utcTimezone = new DateTimeZone('UTC');

        $date = date('Y-m-d H:i:s');

        $time = new DateTime($date, $utcTimezone);

        $time->format('Y-m-d H:i:s');
        $latest = $time->format('Y-m-d H:i:s');

        $laTimezone = new DateTimeZone('Asia/kolkata');

        $time->setTimeZone($laTimezone);

        $current = $time->format('Y-m-d H:i:s');

        $late = "no";
        $half_day = "no";
        if (date('H:i:s', strtotime($current)) > '10:15:00') {
            $late = "yes";
        }
        if (date('H:i:s', strtotime($current)) > '14:00:00') {
            $half_day = "yes";
        }

        $checkExiting = DB::table('attendances')->orderBy('clock_in_time','desc')->where('user_id',$user_id)->whereRaw('date(clock_in_time) = ?',date('Y-m-d'))->first();
        
        if($checkExiting)
        {
            
            DB::table('attendances')->where('id', $checkExiting->id)  // find your user by their email
            ->limit(1)->update([
            "clock_out_time" =>  $latest,
            'user_id'=>$user_id,
            'updated_at'=>$current,
            "clock_out_ip"=>$_SERVER['REMOTE_ADDR']
            ]);
        }
        else{
            DB::table('attendances')->insert( [
                'user_id'=>$user_id,
                'company_id'=>1,
                'location_id'=>1,
                "clock_in_time" =>  $latest,
                "late"=>$late,
                "half_day"=>$half_day,
                "work_from_type"=>"office",
                'created_at'=>$current,
                "clock_in_ip"=>$_SERVER['REMOTE_ADDR']
                ]);
        }

        echo json_encode(['status'=>"success"]);

    }
    public function storeEMP(Request $request)
    {
        
        DB::beginTransaction();
        try {
            $user = new User();
            $user->company_id = 1;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->mobile = $request->mobile;
            $user->country_id = '1';
            $user->country_phonecode = '+91';
            $user->gender = $request->gender;
            $user->locale = 'en';

            if ($request->has('login')) {
                $user->login = $request->login;
            }

            if ($request->has('email_notifications')) {
                $user->email_notifications = $request->email_notifications ? 1 : 0;
            }

            if ($request->hasFile('image')) {
                Files::deleteFile($user->image, 'avatar');
                $user->image = Files::upload($request->image, 'avatar', 300);
            }

            if ($request->has('telegram_user_id')) {
                $user->telegram_user_id = $request->telegram_user_id;
            }

            $user->save();

            $tags = json_decode($request->tags);

            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    // check or store skills
                    $skillData = Skill::firstOrCreate(['name' => strtolower($tag->value)]);

                    // Store user skills
                    $skill = new EmployeeSkill();
                    $skill->user_id = $user->id;
                    $skill->skill_id = $skillData->id;
                    $skill->save();
                }
            }

            if ($user->id) {
                $employee = new EmployeeDetails();
                $employee->user_id = $user->id;
                $this->employeeData($request, $employee);
                $employee->save();

                // To add custom fields data
                if ($request->custom_fields_data) {
                    $employee->updateCustomFieldData($request->custom_fields_data);
                }
            }

            $employeeRole = Role::where('name', 'employee')->first();
            $user->attachRole($employeeRole);

            if ($employeeRole->id != $request->role) {
                $otherRole = Role::where('id', $request->role)->first();
                $user->attachRole($otherRole);
            }

            $user->assignUserRolePermission($request->role);
            $this->logSearchEntry($user->id, $user->name, 'employees.show', 'employee');

            // Commit Transaction
            DB::commit();




        } catch (\Swift_TransportException $e) {
            // Rollback Transaction
            DB::rollback();

            return Reply::error('Please configure SMTP details to add employee. Visit Settings -> notification setting to set smtp '.$e->getMessage(), 'smtp_error');
        } catch (\Exception $e) {
            logger($e->getMessage());
            // Rollback Transaction
            DB::rollback();

            return Reply::error('Some error occurred when inserting the data. Please try again or contact support '. $e->getMessage());
        }



        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => route('employees.index')]);
    }
    public function employeeData($request, $employee): void
    {
        $employee->employee_id = $request->employee_id;
        $employee->address = $request->address;
        $employee->hourly_rate = $request->hourly_rate;
        $employee->slack_username = $request->slack_username;
        $employee->department_id = $request->department;
        $employee->designation_id = $request->designation;
        $employee->reporting_to = $request->reporting_to;
        $employee->about_me = $request->about_me;
        $employee->joining_date = date('Y-m-d', strtotime($request->joining_date));
        $employee->date_of_birth = $request->date_of_birth ? date('Y-m-d', strtotime($request->date_of_birth)) : null;
        $employee->calendar_view = 'task,events,holiday,tickets,leaves';
    //     $employee->probation_end_date = $request->probation_end_date ?  date('Y-m-d', strtotime($request->probation_end_date)) : null;
    //     $employee->notice_period_start_date = $request->notice_period_start_date ? date('Y-m-d', strtotime($request->notice_period_start_date)) : null;
    //     $employee->notice_period_end_date = $request->notice_period_end_date ? date('Y-m-d', strtotime($request->notice_period_end_date)) : null;
    //     $employee->marital_status = $request->marital_status;
    //     $employee->marriage_anniversary_date = $request->marriage_anniversary_date ?  date('Y-m-d', strtotime($request->marriage_anniversary_date)) : null;
    //     $employee->employment_type = $request->employment_type;
    //     $employee->internship_end_date = $request->internship_end_date ? date('Y-m-d', strtotime($request->internship_end_date)) : null;
    //     $employee->contract_end_date = $request->contract_end_date ? date('Y-m-d', strtotime($request->contract_end_date)) : null;
    }

    public function getDataFromSheet()
{
    $client = new \Google_Client();
    $client->setApplicationName('Google Sheets API');
    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
    $client->setAccessType('offline');
    // credentials.json is the key file we downloaded while setting up our Google Sheets API
    $path = __DIR__.'\credentials.json';
    $client->setAuthConfig($path);

    // configure the Sheets Service
    $service = new \Google_Service_Sheets($client);

    $spreadsheetId = '1LWV0Snm1q57ht1M9i1SC2GoQCJZty81jtHXfXLBdXQM';
    $range = 'Sheet1!B2:C'; // Assuming questions are in column A and answers in column B

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    return $values;

}
public function processData()
{
    $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "faq";
        $worksuitePlugins = [];
        $customLink = [];
        $this->checkListCompleted = 5;
        $this->checkListTotal = 6;
        $this->pushSetting = $push_setting;
        $this->user = $user;
        $this->appTheme = $appTheme;
        $this->worksuitePlugins = $worksuitePlugins;
        $this->pusherSettings = $pusher_settings;
        $this->activeTimerCount = 0;
        $this->customLink = $customLink;
        $this->unreadMessagesCount = 0;
        $this->sidebarUserPermissions = $sidebarUserPermissions;
        $this->unreadNotificationCount = 0;
        $this->pageTitle = "FAQ";

    $values = $this->getDataFromSheet();
    
    $data = [];
    $currentIndex = -1;

    foreach ($values as $row) {
        $question = $row[0];
        $answer = $row[1];

        if (!empty($question)) {
            $currentIndex++;
            $data[$currentIndex][] = $question;
        }

        $data[$currentIndex][] = $answer;
    }
    return view('tlr::faq', ['values' => $data], $this->data);

    //return $data;
}
}
