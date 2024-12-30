<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Activity;
use App\Models\Project;
use App\Models\ProjectLog;

class FrontendController extends Controller
{
    public function index(){
        $activities = Activity::get(["id","name"]);
        $projects = Project::get(["id","name"]);
        return Inertia::render("AddTime",compact("activities","projects"));
    }
    public function timeLogs(Request $request){
        return Inertia::render("TimeLogs");
    }
    public function allTimeLog(Request $request){
        $userId = $request->user()->id;
        $startDate = $request->start_date ? $request->start_date : null;
        $endDate = $request->end_date ? $request->end_date : null;
        $projectId = $request->project_id ? $request->project_id : null;
        $length = $request->length ? $request->length : 10;
        $logs = ProjectLog::where("user_id",$userId);
        if($startDate){
            $logs = $logs->where("date",">=",$startDate);
        }
        if($endDate){
            $logs = $logs->where("date","<=",$endDate);
        }
        if($projectId){
            $logs = $logs->where("project_id",$projectId);
        }
        $totalLogs = clone $logs;
        $totalTime = $totalLogs->sum("time");
        $logs = $logs->orderBy("created_at","desc")->paginate($length)->onEachSide(1);
        return json_encode(["items" => $logs,"totalTime" => $totalTime]);
    }
    public function addTimeLog(Request $request){
        $userId = $request->user()->id;
        $log = new ProjectLog($request->except("hours","minutes","time"));
        $log->user_id = $userId;
        $time = (((int) $request->hours * 60) + ((int) $request->minutes));
        $log->time = $time;
        $log->save();
        return json_encode(["status" => "success","message" => "Logs added successfully."]);
    }
    public function employeeSummary(){
        return Inertia::render("EmployeeSummary");
    }
    public function projectSummary(){
        $projects = Project::get(["id","name"]);
        return Inertia::render("ProjectSummary",compact("projects"));
    }
    public function projectView($id){
        $project = Project::findOrfail($id);
        return Inertia::render("ProjectLogs",compact("project"));
    }
    public function allProjectSummary(Request $request){
        $userId = $request->user()->id;
        $startDate = $request->start_date ? $request->start_date : null;
        $endDate = $request->end_date ? $request->end_date : null;
        $projectId = $request->project_id ? $request->project_id : null;
        $length = $request->length ? $request->length : 10;
        $items = ProjectLog::where("user_id",$userId);
        if($startDate){
            $items = $items->where("date",">=",$startDate);
        }
        if($endDate){
            $items = $items->where("date","<=",$endDate);
        }
        if($projectId){
            $items = $items->where("project_id",$projectId);
        }
        $totalLogs = clone $items;
        $totalTime = $totalLogs->sum("time");
        $items = $items->select('project_id','project_id as id',DB::raw('SUM(time) as time'))->groupBy('project_id')->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items,"totalTime" => $totalTime]);
    }
}