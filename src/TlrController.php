<?php

namespace Laraveltlr\Tlr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, DB, Session;

class TlrController extends Controller
{

    
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
        return view('tlr::tlr_month', ['id'=>$id,'logged_user_id'=>$logged_user_id],$this->data);
    }
    public function tlrAdminMonth(Request $request){
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
            $id = Session::get('user_id');
        }else if(isset($request->month)){
            $month = intval($request['month'])+1;
            $id = $request->user;
        }else
        {
            $id = $request->user;
            $month = date('m');
        }

        if (isset($request->year)) {
            $year = $request->year;
        }else{
            $year = date('Y');
        }
        if($request->ajax()){
            $where_str = "1 =?";
            $where_param =array('1');

            if($request->has('search.value')){

                $search = $request->search['value'];

                $where_str .= " and (users.name like \"%{$search}%\""
                            ." or topic_id like \"%{$search}%\""
                            .")";
            }
             $columns = array('point.id','point.user_id','point.from_hour','point.to_hour', 'point.topic_id','point.subject','point.participant','point.point','point.date','users.name');
             if($id != null)
             {

               $point_count = DB::table("point")
                            ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,users.name")
                            ->join('users','point.user_id','=','users.id')
                            ->where('user_id',$id)
                            ->whereRaw('MONTH(date) = ?',[$month])
                            ->whereRaw('YEAR(date) = ?',[$year])
                            ->whereRaw($where_str,$where_param)
                            ->count();

                $Point = DB::table("point")
                        ->selectRaw("*,DATE_FORMAT(point.date, '%d/%m/%Y') as date,users.name,point.id as id")
                        ->join('users','point.user_id','=','users.id')
                        ->where('user_id',$id)
                        ->whereRaw('MONTH(date) = ?',[$month])
                        ->whereRaw('YEAR(date) = ?',[$year])
                        ->whereRaw($where_str,$where_param);

             }
             else
            {
                $point_count = DB::table("point")
                            ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,users.name")
                            ->join('users','point.user_id','=','users.id')
                            ->whereRaw('MONTH(date) = ?',[$month])
                            ->whereRaw('YEAR(date) = ?',[$year])
                            ->whereRaw($where_str,$where_param)
                            ->count();

                $Point = DB::table("point")
                        ->selectRaw("*,DATE_FORMAT(point.date, '%d/%m/%Y') as date,users.name,point.id as id")
                        ->join('users','point.user_id','=','users.id')
                        ->whereRaw('MONTH(date) = ?',[$month])
                        ->whereRaw('YEAR(date) = ?',[$year])
                        ->whereRaw($where_str,$where_param);
            }

            if($request->has('start') && $request->get('length') !='-1'){
            $Point = $Point->take($request->get('length'))
                                    ->skip($request->get('start'));
            }

            if($request->has('order')){
            $sql_order='';
            for ( $i = 0; $i < $request->input('order.0.column'); $i++ )
            {
                $column = $columns[$i];
                if(false !== ($index = strpos($column, ' as '))){
                    $column = substr($column, 0, $index);
                }
                $Point = $Point->orderBy($column,$request->input('order.'.$i.'.dir'));
            }
        }
                $Point = $Point->get()->toArray();
                $array_data = json_decode(json_encode($Point),true);

                    foreach($array_data as $key =>$value)
                    {
                        $team_member = DB::table("users")->select('name','id')->where('id',$value['user_id'])->get()->toArray();

                        $topic =  DB::table("topic")->select('topic','id')->where('id',$value['topic_id'])->get()->toArray();

                        $users = array_column($team_member,'name');
                        $topics = array_column($topic,'topic');
                        $array_data[$key]['user_id'] = $users;
                        $array_data[$key]['topic_id'] = $topics;
                    }

            $response['iTotalDisplayRecords'] =$point_count;
            $response['iTotalRecords'] = $point_count;

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $array_data;

            return $response;
        }


        $all_users = [];
        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';
            

            $all_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->toArray();
        //dd($all_users);
        }
        return view('tlr::tlr_month_admin',['all_users'=>$all_users],$this->data);
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
            $id = $request->user;
        } else {
            $id = Auth::id();
        }

        Session::put("user_name", $id);


        $logged_user_id = $id;

        if ($request->ajax()) {
            $where_str = "1 =?";
            $where_param = array('1');

            $columns = array('id', 'date', 'point');

            if (!empty($request->from_date)) {

                $Point = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,SUM(point) as point,DATE_FORMAT(date, '%m') as month")
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->where('user_id', $id)
                    ->orderBy('month', 'ASC')
                    ->groupBy('date')
                    ->whereRaw($where_str, $where_param);
            } else {

                $Point = DB::table("point")
                    ->selectRaw("*,DATE_FORMAT(date, '%d/%m/%Y') as date,SUM(point) as point,DATE_FORMAT(date, '%m') as month")
                    ->whereRaw('YEAR(date) = ?', [$year])
                    ->where('user_id', $id)
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
            

            $all_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->toArray();
        //dd($all_users);
        }

        return view('tlr::tlr_year', ['all_users' => $all_users, 'id' => $id, 'logged_user_id' => $logged_user_id],$this->data);
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
            $participant =DB::table('users')->select('name','id')->get()->pluck('name','id')->toArray();
            unset($participant[$id]);
            return response()->json(["participant" => $participant]);
        }

        $topic =  DB::table("topic")->select('topic','id')->get()->pluck('topic','id')->toArray();
            unset($topic[10]);
            unset($topic[11]);

        $users =DB::table('users')->select('name','id')->get()->pluck('name','id')->toArray();


        $skills = ['Unsatisfactory','Less than satisfactory','Fully satisfactory','Excellent'];
        return view('tlr::tlr_create',['topic'=>$topic,'users'=>$users,'skills'=>$skills],$this->data);
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

        $this->validate($request,$rules);
        
        $data = $request->skill;
        $topic_point = DB::table('topic')->select('point')->where('id',$request->topic)->get()->pluck('point')->toArray();
       
        if($data==1)
        {
          $point_score=1;
          $request['subject'] = "Less than satisfactory";
        }elseif($data==2){
          $point_score=3;
          $request['subject'] = "Fully satisfactory";
        }elseif($data==3){
          $point_score=5;
          $request['subject'] = "Excellent";
        }else{
        $point_score =implode(',',$topic_point);
        }
        $topic_id = $request->topic; 
        $user_id = $request->organizer;
        $date = date('Y-m-d',strtotime($request->date));
        $subject = $request->subject;
        
        if($request->from_hour != "10:00 AM" && $request->to_hour != "07:00 PM" )
        {
            $from_hour = $request->from_hour;
            $to_hour = $request->to_hour;
        }else{
            $from_hour = "";
            $to_hour = "";
        }
        DB::table('point')->updateOrInsert([
            'point'=>$point_score,
            'topic_id'=>$topic_id,
            'user_id'=>$user_id,
            'date'=>$date,
            'subject'=>$subject,
            'from_hour'=>$from_hour,
            'to_hour'=>$to_hour,
        ]);

        $participant = $request->participant;

        if($participant != null)
        {
            foreach($participant as $value)
            {   
                $topic_id = $request->topic;
                $participant = "1";
                $user_id = $value;
                $subject = $request->subject;
                if($request->from_hour != "10:00 AM" && $request->to_hour != "07:00 PM" )
                {
                    $from_hour = $request->from_hour;
                    $to_hour = $request->to_hour;
                }else{
                    $from_hour = "";
                    $to_hour = "";
                }
                $date = date('Y-m-d',strtotime($request->date));
                $point_score=5;
                
                DB::table('point')->updateOrInsert([
                    'point'=>$point_score,
                    'topic_id'=>$topic_id,
                    'user_id'=>$user_id,
                    'date'=>$date,
                    'subject'=>$subject,
                    'from_hour'=>$from_hour,
                    'to_hour'=>$to_hour,
                ]);
            }
        }

       return redirect()->route('tlr_month_admin')
                         ->with('message','Points Added Successfully.')
                         ->with('message_type','success');
    }

    public function tlrAdminMonthDelete(Request $request)
    {
            DB::table("point")->where('id',$request->id)->delete();

            return back()->with('message','Point deleted successfully')
                        ->with('message','success');
    }

    public function monthset($month)
    {  
        Session::put("month",$month);
        return redirect()->route('point.index');
    }

    public function monthsetuser($month)
    {  
        Session::put("month",$month);
        return redirect()->route('user.viewindex');
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
            $month = intval($request['month'])+1;
        }else{

            $month = date('m');

        }
        if (isset($request->year)) {
            $year = $request->year;
        }else{

            $year = date('Y');

        }

        $id = Auth::id();
        $logged_user_id = Auth::id();
        if($request->ajax()){
            $where_str = "1 =?";
            $where_param =array('1');

            if($request->has('search.value')){
                $search = $request->search['value'];
                $where_str .= " and (users.name like \"%{$search}%\""
                ." or point.point like \"%{$search}%\""
                .")";
            }

            $columns = array('users.name','point.point','point.id','point.user_id','point.from_hour','point.to_hour', 'point.topic_id','point.subject','point.participant','point.date');

            $Point = DB::table("point")
            ->selectRaw("*,DATE_FORMAT(point.date, '%d/%m/%Y') as date,SUM(point.point) as point,users.name")
            ->join('users','point.user_id','=','users.id')
            ->whereRaw('MONTH(date) = ?',[$month])
            ->whereRaw('YEAR(date) = ?',[$year])
            ->groupby('user_id')
            ->whereRaw($where_str,$where_param);

            $Point_count = $Point->get()->toArray();        

            if($request->has('start') && $request->get('length') !='-1'){
                $Point = $Point->take($request->get('length'))
                ->skip($request->get('start'));
            }

            if($request->has('order')){
                $sql_order='';
                 $column = $columns[$request->input('order.0.column')];
                 if($column == 'point.point'){
                    $Point = $Point->orderBy('point',$request->input('order.0.dir'));     
                 }else{
                    $Point = $Point->orderBy($column,$request->input('order.0.dir')); 
                 }
                
            }       
            $Point = $Point->get()->toArray();
            $response['iTotalDisplayRecords'] =count($Point_count);
            $response['iTotalRecords'] = count($Point_count);

            $response['sEcho'] = intval($request->get('sEcho'));

            $response['aaData'] = $Point;

        return $response;
     }

        if ($user->roles[0]['name'] == 'admin') {
            $logged_user_id = '';

        }
        return view('tlr::tlr_point',['id'=>$id,'logged_user_id'=>$logged_user_id],$this->data);
    }

    public function userset($month,$user_id)
    {  
        Session::put("month",$month);
        Session::put("user_id",$user_id);
        return redirect()->route('tlr_month');
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

            if ($request->get('search') ['value'] != "") {
                $search = $request->get('search') ['value'];
                $where_str .= " and ( topic like \"%{$search}%\""
                             
                    . ")";
            }
            $user = DB::table("topic")
            ->select('id','topic','point')
            ->whereRaw($where_str, $where_params);
            
            $user_count =  DB::table("topic")
                ->whereRaw($where_str, $where_params)
                ->count();

            $columns = ['id','topic','point'];
            $order_columns = ['topic'];

             if($request->has('start') && $request->get('length') !='-1'){
            $user = $user->take($request->get('length'))
                                    ->skip($request->get('start'));
            }
            if($request->has('order')){
                $sql_order='';
                for ( $i = 0; $i <= $request->input('order.0.column'); $i++ )
                {
                    $column = $order_columns[$i];
                    if(false !== ($index = strpos($column, ' as '))){
                        $column = substr($column, 0, $index);
                    }
                    $user = $user->orderBy($column,$request->input('order.'.$i.'.dir'));
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

       
        return view('tlr::tlr_topic',['totalpoint'=>$totalpoint],$this->data);
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

        return view('tlr::topic_create',$this->data);
    }

    public function topicstore(Request $request)
    {    
        $this->validate($request,[
            'topic' => 'required',
            'point' => 'required',
        ]);

                DB::table('topic')->updateOrInsert([
                    "topic"=>  $request->topic,
                    "point"=>  $request->point,
                ]);

           return redirect()->route('topic.index')
                         ->with('message','Topic Added Successfully.')
                         ->with('message_type','success');
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
        

        $topic = DB::table('topic')->select('id','topic','point')->where('id',$rowid)->first();
        return view('tlr::topic_edit',compact('topic'),$this->data);
    }

    public function topicupdate(Request $request ,$id)
    {
             
         $this->validate($request,[
             'topic' => 'required',
             'point' => 'required',
         ]);
 
        //  $topic = Topic::find($id);
        //  $topic->topic = $request->topic;
        //  $topic->point = $request->point;
      
        //  $topic->save();
         DB::table('topic')->updateOrInsert(['id'=>$id],[
            "topic"=>  $request->topic,
            "point"=>  $request->point,
         ]);
 
         return redirect()->route('topic.index')
         ->with('message','Record Updated successfully')
         ->with('message_type','success');
       

    }

    public function topicdelete(Request $request)
    {
         DB::table('topic')->where('id',$request->id)->delete();
 
         return back()->with('message','Project deleted successfully')
                      ->with('message','success');
    }

}
