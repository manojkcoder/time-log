<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Client;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(){
        $clients = Client::get(["id","name"]);
        return Inertia::render("Admin/Projects",compact("clients"));
    }
    public function all(Request $request){
        $length = $request->length ? $request->length : 10;
        $items = Project::orderBy("id","desc")->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items]);
    }
    public function createUpdate(Request $request){
        if($request->id){
            $project = Project::findOrfail($request->id);
            $project->update($request->all());
            return json_encode(["status" => "success","message" => "Project updated successfully."]);
        }else{
            $project = new Project($request->all());
            $project->save();
            return json_encode(["status" => "success","message" => "Project added successfully."]);
        }
    }
    public function updateStatus(Request $request,$id){
        $project = Project::findOrFail($id);
        $project->status = $request->status;
        $project->save();
        return json_encode(["status" => "success","message" => "Status updated successfully"]);
    }
    public function destroy($id){
        $project = Project::findOrFail($id);
        $project->delete();
        return Inertia::location(route("admin.projects"));
    }
}
