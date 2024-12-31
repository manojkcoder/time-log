<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;
use App\Models\Activity;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\User;

class ProjectLogController extends Controller
{
    public function index(){
        $activities = Activity::get(["id","name"]);
        $projects = Project::get(["id","name"]);
        $users = User::get(["id","name"]);
        return Inertia::render("Admin/ProjectLogs",compact("activities","projects","users"));
    }
    public function all(Request $request){
        $length = $request->length ? $request->length : 10;
        $startDate = $request->start_date ? $request->start_date : null;
        $endDate = $request->end_date ? $request->end_date : null;
        $userId = $request->user_id ? $request->user_id : null;
        $projectId = $request->project_id ? $request->project_id : null;
        $items = ProjectLog::whereNotNull("user_id");
        if($startDate){
            $items = $items->where("date",">=",$startDate);
        }
        if($endDate){
            $items = $items->where("date","<=",$endDate);
        }
        if($userId){
            $items = $items->where("user_id",$userId);
        }
        if($projectId){
            $items = $items->where("project_id",$projectId);
        }
        $totalLogs = clone $items;
        $totalTime = $totalLogs->sum("time");
        $items = $items->orderBy("date","desc")->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items,"totalTime" => $totalTime]);
    }
    public function exportLogs(Request $request){
        $startDate = $request->start_date ? $request->start_date : null;
        $endDate = $request->end_date ? $request->end_date : null;
        $userId = $request->user_id ? $request->user_id : null;
        $projectId = $request->project_id ? $request->project_id : null;
        $items = ProjectLog::whereNotNull("user_id");
        if($startDate){
            $items = $items->where("date",">=",$startDate);
        }
        if($endDate){
            $items = $items->where("date","<=",$endDate);
        }
        if($userId){
            $items = $items->where("user_id",$userId);
        }
        if($projectId){
            $items = $items->where("project_id",$projectId);
        }
        $items = $items->orderBy("id","desc")->get();
        $csvFileName = "project_logs_" . date("Ymd_His") . ".csv";
        $headers = ["Content-Type" => "text/csv","Content-Disposition" => "attachment; filename='" . $csvFileName . "'"];
        $columns = ["ID","Project","Activity","User","Description","Project Date","Spent Time"];
        $callback = function() use($items,$columns){
            $file = fopen('php://output','w');
            fputcsv($file,$columns);
            foreach($items as $item){
                $timeFormat = "";
                if($item->time){
                    $hours = floor($item->time / 60);
                    $minutes = $item->time % 60;
                    $timeFormat = sprintf("%d:%02d%s",$hours,$minutes,' hours');
                }
                fputcsv($file,[$item->id,$item->project_name,$item->activity_name,$item->user_name,$item->description,$item->date,$timeFormat]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
    public function Update(Request $request){
        $log = ProjectLog::findOrfail($request->id);
        $log->update($request->except("hours","minutes","time"));
        $time = (((int) $request->hours * 60) + ((int) $request->minutes));
        $log->time = $time;
        $log->save();
        return json_encode(["status" => "success","message" => "Logs updated successfully."]);
    }
    public function destroy($id){
        $log = ProjectLog::findOrFail($id);
        $log->delete();
        return Inertia::location(route("admin.project-logs"));
    }
    public function projectSummary(){
        $projects = Project::get(["id","name"]);
        return Inertia::render("Admin/ProjectSummary",compact("projects"));
    }
    public function allProjectSummary(Request $request){
        $startDate = $request->start_date ? $request->start_date : null;
        $endDate = $request->end_date ? $request->end_date : null;
        $projectId = $request->project_id ? $request->project_id : null;
        $length = $request->length ? $request->length : 10;
        $items = ProjectLog::whereNotNull("user_id");
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
    public function viewProjectSummary($id){
        $project = Project::findOrfail($id);
        $users = User::get(["id","name"]);
        return Inertia::render("Admin/ViewProjectLog",compact("project","users"));
    }
    public function employeeSummary(){
        $users = User::get(["id","name"]);
        return Inertia::render("Admin/EmployeeSummary",compact("users"));
    }
    public function allEmployeeSummary(Request $request){
        $startDate = $request->start_date ? $request->start_date : null;
        $endDate = $request->end_date ? $request->end_date : null;
        $userId = $request->user_id ? $request->user_id : null;
        $length = $request->length ? $request->length : 10;
        $items = ProjectLog::whereNotNull("user_id");
        if($startDate){
            $items = $items->where("date",">=",$startDate);
        }
        if($endDate){
            $items = $items->where("date","<=",$endDate);
        }
        if($userId){
            $items = $items->where("user_id",$userId);
        }
        $totalLogs = clone $items;
        $totalTime = $totalLogs->sum("time");
        $items = $items->select('project_id','user_id',DB::raw('SUM(time) as time'))->groupBy('project_id','user_id')->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items,"totalTime" => $totalTime]);
    }
    public function viewEmployeeSummary($userId,$projectId){
        $project = Project::findOrfail($projectId);
        $user = User::findOrfail($userId);
        return Inertia::render("Admin/ViewEmployeeLog",compact("project","user"));
    }

    
}
