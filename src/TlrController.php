<?php

namespace Laraveltlr\Tlr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, DB, Session, DateTimeZone, DateTime, Validator, PDF, Mail, Hash;
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
        $data_user = DB::table('employee_details')->select('user_id')->where('employee_id', $employee_id)->first();
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

        $checkExiting = DB::table('attendances')->orderBy('clock_in_time', 'desc')->where('user_id', $user_id)->whereRaw('date(clock_in_time) = ?', date('Y-m-d'))->first();

        if ($checkExiting) {

            DB::table('attendances')->where('id', $checkExiting->id)  // find your user by their email
                ->limit(1)->update([
                    "clock_out_time" =>  $latest,
                    'user_id' => $user_id,
                    'updated_at' => $current,
                    "clock_out_ip" => $_SERVER['REMOTE_ADDR']
                ]);
        } else {
            DB::table('attendances')->insert([
                'user_id' => $user_id,
                'company_id' => 1,
                'location_id' => 1,
                "clock_in_time" =>  $latest,
                "late" => $late,
                "half_day" => $half_day,
                "work_from_type" => "office",
                'created_at' => $current,
                "clock_in_ip" => $_SERVER['REMOTE_ADDR']
            ]);
        }

        echo json_encode(['status' => "success"]);
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

            return Reply::error('Please configure SMTP details to add employee. Visit Settings -> notification setting to set smtp ' . $e->getMessage(), 'smtp_error');
        } catch (\Exception $e) {
            logger($e->getMessage());
            // Rollback Transaction
            DB::rollback();

            return Reply::error('Some error occurred when inserting the data. Please try again or contact support ' . $e->getMessage());
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
        $path = __DIR__ . '/credentials.json';
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

    public function aptitude(Request $request)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "aptitude";
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
        $this->pageTitle = "Aptitude";


        if ($request->ajax()) {

            $where_str = "1=?";
            $where_param = array('1');

            if ($request->get('search')['value'] != "") {

                $search = $request->get('search')['value'];

                $where_str .= " and (question like \"%{$search}%\""
                    . ")";
            }

            $columns = array('id', 'question');

            $sort_columns = array('id', 'question');

            $quiz_count = DB::table('aptitude')->select('id')->whereRaw($where_str, $where_param)
                ->count();

            $quiz = DB::table('aptitude')->select($columns)->whereRaw($where_str, $where_param)
                ->orderBy('id', 'ASC');


            if ($request->has('start') && $request->get('length') != '-1') {
                $quiz = $quiz->take($request->get('length'))
                    ->skip($request->get('start'));
            }
            if ($request->has('order')) {
                for ($i = 0; $i < $request->get('order.0.column'); $i++) {
                    $column = $sort_columns[$i];
                    if (false !== ($index = strpos($column, ' as '))) {
                        $column = substr($column, 0, $index);
                    }
                    $quiz = $quiz->orderBy($column, $request->get('order.' . $i . '.dir'));
                }
            }

            $quiz = $quiz->get();
            $response['iTotalDisplayRecords'] = $quiz_count;
            $response['iTotalRecords'] = $quiz_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $quiz->toArray();

            return $response;
        }
        $technology = DB::table('aptitude_technology')->select('id', 'name')->get()->toArray();
        $technology = json_decode(json_encode($technology), true);

        return view('tlr::aptitude', ['technology' => $technology], $this->data);
    }

    public function aptitudeEdit($aptitude_id)
    {

        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "aptitude-edit";
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
        $this->pageTitle = "Aptitude Edit";

        // $aptitude_edit = Aptitude::find($aptitude_id);
        $aptitude_edit = DB::table('aptitude')->find($aptitude_id);
        $aptitude_options = DB::table('aptitude_options')->where('aptitude_id', $aptitude_id)->get()->toArray();
        $aptitude_options = json_decode(json_encode($aptitude_options), true);
        // $aptitude_options = Aptitudeoptions::where('aptitude_id',$aptitude_id)->get()->toArray();

        $final = [];
        foreach ($aptitude_options as $aptitude_option) {
            $hello = [];
            $key = 'options';
            $key1 = 'is_true';
            $hello[$key] = $aptitude_option['option'];
            if ($aptitude_option['is_true']) {
                $hello[$key1] = $aptitude_option['is_true'];
            }
            $final[] = $hello;
        }

        return view('tlr::aptitude_edit', ['aptitude_edit' => $aptitude_edit, 'final' => $final], $this->data);
        // return view('aptitude.edit',compact('aptitude_edit','final'));

    }
    public function aptitudeUpdate(Request $request, $aptitude_id)
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "aptitude-edit";
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
        $this->pageTitle = "Aptitude Update";
        // dd($request->all());
        $aptitude_input = $request->all();

        $options = $aptitude_input['undefined'];

        $rules = [
            'question' => 'required',
            'undefined.undefined.0.options' => 'required',
            'undefined.undefined.1.options' => 'required'
        ];

        $messages = [
            'undefined.undefined.0.options.required' => 'this field is required',
            'undefined.undefined.1.options.required' => 'this field is required'
        ];

        $count = count($options['undefined']);

        for ($i = 2; $i < $count; $i++) {
            if (isset($options['undefined'][$i])) {
                $rules['undefined.undefined.' . $i . '.options'] = 'required';
            }
        }

        for ($i = 2; $i < $count; $i++) {
            $messages['undefined.undefined.' . $i . '.options.required'] = 'this field is required';
        }

        $validator = Validator::make($aptitude_input, $rules, $messages);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator->errors())
                ->with('message', 'Unable to add details.')
                ->with('message_type', 'danger');
        }



        $quiz_update = DB::table('aptitude')->where('id', $aptitude_id)->update([
            'question' => $aptitude_input['question'],
        ]);


        DB::table('aptitude_options')->where('aptitude_id', $aptitude_id)->delete();

        foreach ($options as $option) {
            foreach ($option as $value) {
                if (isset($value['is_true'])) {
                    $is_true = '1';
                } else {
                    $is_true = '0';
                }
                DB::table('aptitude_options')->insert(['option' => $value['options'], 'is_true' => $is_true, 'aptitude_id' => $aptitude_id]);
            }
        }

        return redirect()->route('aptitude')->with('message', 'Aptitude Successfully Added.')
            ->with('message_type', 'success');
    }

    public function aptitudeCreate()
    {
        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "aptitude-create";
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
        $this->pageTitle = "Aptitude Add";

        $technology = DB::table('aptitude_technology')->select('id', 'name')->get()->toArray();
        $technology = json_decode(json_encode($technology), true);
        return view('tlr::aptitude_create', ['technology' => $technology], $this->data);
    }

    public function aptitudeStore(Request $request)
    {

        $aptitude_input = $request->all();
        $options = $aptitude_input['undefined'];

        $rules = [
            'question' => 'required',
            'undefined.undefined.0.options' => 'required',
            'undefined.undefined.1.options' => 'required'
        ];

        $messages = [
            'undefined.undefined.0.options.required' => 'this field is required',
            'undefined.undefined.1.options.required' => 'this field is required'
        ];

        $a = 0;

        foreach ($aptitude_input['undefined']['undefined'] as $key => $value) {

            if ($a = 0) {

                if (!isset($value['is_true'])) {

                    $rules['undefined.undefined.0.is_true'] = 'required';
                    $messages['undefined.undefined.0.is_true.required'] = 'check atleast one true answer';
                } else {
                    $a = 1;
                    unset($rules['undefined.undefined.0.is_true']);
                    unset($messages['undefined.undefined.0.is_true']);
                }
            }
        }

        $count = count($options['undefined']);

        for ($i = 2; $i < $count; $i++) {
            if (isset($options['undefined'][$i])) {
                $rules['undefined.undefined.' . $i . '.options'] = 'required';
            }
        }

        for ($i = 2; $i < $count; $i++) {
            $messages['undefined.undefined.' . $i . '.options.required'] = 'this field is required';
        }

        $validator = Validator::make($aptitude_input, $rules, $messages);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator->errors())
                ->with('message', 'Unable to add details.')
                ->with('message_type', 'danger');
        }
        DB::table('aptitude')->insert(['question' => $aptitude_input['question'], 'language' => $aptitude_input['tech_id']]);

        $lastInsertedId = DB::getPdo()->lastInsertId();


        foreach ($options as $option) {
            foreach ($option as $value) {
                if (isset($value['is_true'])) {
                    $is_true = '1';
                } else {
                    $is_true = '0';
                }
                DB::table('aptitude_options')->insert(['option' => $value['options'], 'is_true' => $is_true, 'aptitude_id' => $lastInsertedId]);
            }
        }

        return redirect()->route('aptitude')->with('message', 'Quiz Successfully Added.')
            ->with('message_type', 'success');
    }
    public function aptitudeDelete($aptitude_id)
    {
        DB::table('aptitude')->where('id', $aptitude_id)->delete();

        return back()->with('message', 'Aptitude Delted Successfully.')
            ->with('message_type', 'success');
    }

    public function saveTechnology(Request $request)
    {
        //dd($request->all());
        DB::table('aptitude_technology')->insert(['name' => $request->hidden]);

        return back()->with('message', 'Technology Successfully Added.')
            ->with('message_type', 'success');
    }

    public function ImportQuestion(Request $request)
    {
        $name = $request->excel->getClientOriginalName();
        $request->excel->move(public_path(), $name);
        $readerData = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $readerData->setReadDataOnly(TRUE);
        $spreadsheetData = $readerData->load($name);

        $worksheet = $spreadsheetData->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $rowData = $worksheet->rangeToArray('A1:' . $highestColumn . $highestRow, NULL, true, false);

        foreach ($rowData as $key => $value) {
            $AptitudeTech = DB::table('aptitude_technology')->select('id')->where('name', $value[6])->first();
            $AptitudeTech = json_decode(json_encode($AptitudeTech), true);
            if (empty($AptitudeTech)) {

                return response()->json(['status' => false, 'message' => 'Please First Add Technology', 'message_type' => 'error']);
            } else {

                $aptitude = DB::table('aptitude')->select('id')->where('question', $value[0])->first();
                if (empty($aptitude)) {
                    DB::table('aptitude')->insert(['question' => $value[0], 'language' => $AptitudeTech['id'], 'level' => $value[7]]);
                    $lastInsertedId = DB::getPdo()->lastInsertId();
                    $option_array = array_slice($rowData[$key], 1, -3);
                    $option_value = ["A", "B", "C", "D"];
                    $is_true = array_search(strtoupper($value[5]), $option_value, true);
                    foreach ($option_array as $key1 => $value) {

                        if ($is_true == $key1) {
                            $is_true = '1';
                        } else {
                            $is_true = '0';
                        }

                        DB::table('aptitude_options')->insert(['option' => trim($value), 'is_true' => $is_true, 'aptitude_id' => $lastInsertedId]);
                    }
                }
            }
        }
        return response()->json(['status' => true, 'message' => 'Your File Has Been Uploaded Successfully', 'message_type' => 'success']);
    }

    public function StartPage($token)
    {

        $current_time =  strtotime(date('H:i:s'));
        $current_date =  date('Y-m-d');
        $check_token_expire = DB::table('link_resets')->where('token', $token)->first();
        $check_token_expire = json_decode(json_encode($check_token_expire), true);
        //dd($check_token_expire);
        if (!isset($check_token_expire['created_at'])) {
            return view('tlr::aptitude_link_expire');
        } else {

            $timestamp = (strtotime($check_token_expire['created_at']));

            // dd($timestamp);
            $time = strtotime(date('H:i:s', $timestamp));
            $date = date('Y-m-d', $timestamp);
            $minute_diff = ($current_time - $time) / 60;
            if ($current_date == $date) {
                if ($minute_diff <= 60) {
                    if ($check_token_expire['level'] != "none") {
                        $question = DB::table('aptitude')->where('language', $check_token_expire['que_type'])->where('level', $check_token_expire['level'])->get()->toArray();
                    } else {
                        $question = DB::table('aptitude')->where('language', '1')->get()->toArray();
                    }

                    $question = json_decode(json_encode($question), true);
                    $check_entry = DB::table('random_questions')->select('question_id')->where('token_id', $check_token_expire['id'])->get()->toArray();
                    $check_entry = json_decode(json_encode($check_entry), true);
                    if (empty($check_entry)) {
                        foreach ($question as $key => $value) {
                            $question_id = $value['id'];
                            DB::table('random_questions')->insert(['token_id' => $check_token_expire['id'], 'question_id' => $question_id]);
                        }
                    }
                    $get_question_id =  DB::table('random_questions')->select('question_id')->where('token_id', $check_token_expire['id'])->pluck('question_id');
                    $get_question_id = json_decode(json_encode($get_question_id), true);
                    $get_question = DB::table('aptitude')->select('question', 'id')->whereIn('id', $get_question_id)->get()->toArray();
                    $get_question = json_decode(json_encode($get_question), true);
                    foreach ($get_question as $key => $value) {
                        $options =  DB::table('aptitude_options')->select('option', 'is_true')->where('aptitude_id', $value['id'])->get()->toArray();
                        $options = json_decode(json_encode($options), true);
                        $array1 = array_column($options, 'option');
                        $get_question[$key]['choices'] = $array1;
                        $answer =  array_search(1, array_column($options, 'is_true'));
                        $get_question[$key]['correctAnswer'] = $answer;

                        $get_question[$key]['title'] = $get_question[$key]['question'];
                        unset($get_question[$key]['question']);
                        $get_question[$key]['pointerEvents'] = false;
                        $get_question[$key]['secondsLeft'] = 180;
                        $get_question[$key]['AnsweredQue'] = "";
                    }
                    return view('tlr::startpage', ['token' => $token, 'question' => $get_question]);
                } else {
                    DB::table('random_questions')->where('token_id', $check_token_expire['id'])->delete();
                    DB::table('link_resets')->where('token', $token)->limit(1)->delete();
                    return view('tlr::aptitude_link_expire');
                }
            } else {
                DB::table('random_questions')->where('token_id', $check_token_expire['id'])->delete();
                DB::table('link_resets')->where('token', $token)->limit(1)->delete();
                return view('tlr::aptitude_link_expire');
            }
        }
    }

    public function saveToken(Request $request)
    {

        if ($request['level'] != "none") {
            $aptitude =  DB::table('aptitude')->where('language', $request['hidden'])->where('level', $request['level'])->first();
        } else {
            $aptitude = "Not Null";
        }

        // dd(empty($aptitude));
        if (empty($aptitude)) {
            return response()->json(['status' => false, 'message' => 'First Please Add Question for Your requirement', 'message_type' => 'error']);
        } else {
            DB::table('link_resets')->insert(['token' => $request['token'], 'que_type' => $request['hidden'], 'level' => $request['level'], 'created_at' => date('Y-m-d H:i:s')]);

            return response()->json(['status' => true, 'message' => 'Your Link Copied Successfully', 'message_type' => 'success']);
        }
    }

    public function generate(Request $request, $token)
    {
        // dd($request->all());

        // $check_token_expire =  DB::table('link_resets')->select('*')->where('token', $token)->first();
        $useranswer = $request->radioBtnChecked;
        $lastanswer = $request->lastCheckedVal;
        array_pop($useranswer);
        array_push($useranswer, $lastanswer);
        $question = $request->allquestion;
        $correct = $request->correct;
        $skip = $request->skip;
        $wrong = $request->wrong;
        $username = $request->user;
        $useremail = $request->email;

        $pdf = PDF::loadView('tlr::aptitude_result', compact('username', 'question', 'useranswer', 'correct', 'skip', 'wrong'));

        $path = public_path('user-uploads/');
        $fileName =  $username . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);

        $pdf = public_path('user-uploads/' . $fileName);

        $data = array(
            'username' => $username,
            'useremail' => $useremail,
            'question' => $question,
            'useranswer' => $useranswer,
            'correct' => $correct,
            'skip' => $skip,
            'wrong' => $wrong,
        );



        $this->SendAptitudeMail($data, $pdf);



        // Random_question::where('token_id', $check_token_expire['id'])->delete();
        // linkreset::where('token', $token)->first()->delete();

        return response()->download($pdf);
    }
    public function SendAptitudeMail($data, $pdf)
    {
        $mail =  Mail::send([], $data, function ($message) use ($data, $pdf) {
            $message->from('noreply-worksuit@thinktanker.in');
            $message->to('hr@thinktanker.in')->subject("Apptitude result");
            $message->attach($pdf);
        });
    }

    public function yearlyReport(Request $request)
    {

        $user = session()->get('user');
        $sidebar_user_perms = session()->get('sidebar_user_perms');
        $pusher_settings = session()->get('pusher_settings');
        $push_setting = session()->get('push_setting');
        $id = $user->id;
        $appTheme = session()->get('admin_theme');
        $sidebarUserPermissions = $sidebar_user_perms;
        $this->currentRouteName = "yearly-report";
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
        $this->pageTitle = "yearly-report";

        $year = date('Y');
        $currentMonth = date('n');

        if ($request->ajax()) {

            $where_str = '1 = ?';
            $where_params = [1];

            if ($request->has('sSearch')) {
                $search = $request->get('sSearch');
                $where_str .= " and (users.name like \"%{$search}%\""
                    . ")";
            }

            $usersData = DB::table('users')
                ->where('status', 'active')
                ->whereRaw($where_str, $where_params)
                ->get();

            $missingTaskCounts = [];

            foreach ($usersData as $user) {
                $missingCount = 0;

                for ($month = 1; $month <= $currentMonth; $month++) {
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $dayOfWeek = date('N', strtotime($date));

                        if ($dayOfWeek >= 6) {
                            continue;
                        }

                        $taskExists = DB::table('sub_tasks')
                            ->where('assigned_to', $user->id)
                            ->whereDate('created_at', $date)
                            ->exists();

                        if (!$taskExists) {
                            $missingCount++;
                        }
                    }
                }

                if ($missingCount > 0) {
                    $missingTaskCounts[] = ['name' => $user->name, 'missing_count' => $missingCount];
                }
            }

            // Sorting
            $columns = ['name', 'missing_count'];
            if ($request->has('iSortCol_0')) {
                $sortColumnIndex = intval($request->get('iSortCol_0'));
                $sortDirection = $request->get('sSortDir_0', 'asc');
                $sortColumn = $columns[$sortColumnIndex];
                $missingTaskCounts = collect($missingTaskCounts)->sortBy($sortColumn, SORT_REGULAR, $sortDirection === 'desc')->values()->all();
            }

            // Pagination
            $perPage = $request->get('iDisplayLength', 10);
            $currentPage = $request->get('iDisplayStart', 0) / $perPage + 1;
            $users = array_slice($missingTaskCounts, ($currentPage - 1) * $perPage, $perPage);

            $users_count = count($missingTaskCounts);

            $response = [
                'iTotalDisplayRecords' => $users_count,
                'iTotalRecords' => $users_count,
                'sEcho' => intval($request->get('sEcho')),
                'aaData' => $users
            ];

            return response()->json($response);
        }

        return view('tlr::yearlyreport', $this->data);
    }

    public function Timetracker(Request $request)
    {
        //dd($request->all());
        DB::table('time_tracker')->updateOrInsert(
            ['date' => $request->date, 'user_id' => $request->user_id],
            [
                'user_id' => $request->user_id,
                'username' => $request->username,
                'email' => $request->email,
                'total_time' => $request->total_time,
                'total_key_count' => $request->total_key_count,
                'total_mouse_move' => $request->total_mouse_move,
                'date' => $request->date,
                'status' => $request->status,
                'user_img' => $request->user_img,
                'sync' => $request->sync,
            ]
        );

        return response()->json(['message' => 'success', 'Record Added Successfully'], 200);
    }


    public function Dailyreport(Request $request)
    {

        DB::table('daily_record')->insert([
            'user_id' => $request->user_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_time' => $request->total_time,
            'date' => $request->date,
            'sync' => $request->sync,
        ]);

        return response()->json(['message' => 'success', 'Record Added Successfully'], 200);
    }

    public function loginTimetracker(Request $request)
    {
        if ($request->email == null || $request->password == null) {

            return response()->json(['error' => 'Unauthorised', 'status' => 401, 'message' => "Please Enter Email And Password "], 401);
        } else {
            $user =  DB::table('users')->select('email', 'name', 'password', 'image')->where('email', $request->email)->first();
            //dd($user);
            $user = json_decode(json_encode($user), true);
            if ($user != null) {
                $useremail = DB::table('users')->select('email', 'id')->where('email', $request->email)->first();
                $useremail = json_decode(json_encode($useremail), true);
               // $profile_pic = public_path('user-uploads/').$user['image'];
                $profile_pic = public_path('user-uploads/' . $user['image']);

            }

            if ($user != null) {
                $hash_password = Hash::check($request->password, $user['password']);
            } else {
                $hash_password = false;
            }

            if ($hash_password == true && $useremail['email'] != null) {

                $data = [
                    'email' => $request->email,
                    'password' => $request->password
                ];

                if (auth()->attempt($data)) {
                    // dd(session()->getId());
                    $token = session()->getId();

                    return response()->json(['token' => $token, 'user_id' => $useremail['id'], 'status' => 200, 'username' => $user['name'], 'email' => $user['email'], 'profile_pic' => $profile_pic], 200);
                } else {
                    return response()->json(['error' => 'Unauthorised', 'status' => 401], 401);
                }
            } else {
                return response()->json(['error' => 'Unauthorised', 'status' => 401, 'message' => "Please Enter valid Email Or Password "], 401);
            }
        }
    }
}
