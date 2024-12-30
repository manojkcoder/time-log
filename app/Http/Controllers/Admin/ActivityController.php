<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Activities");
    }
    public function all(Request $request){
        $length = $request->length ? $request->length : 10;
        $items = Activity::orderBy("id","desc")->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items]);
    }
    public function createUpdate(Request $request){
        if($request->id){
            $activity = Activity::findOrfail($request->id);
            $activity->update($request->all());
            return json_encode(["status" => "success","message" => "Activity updated successfully."]);
        }else{
            $activity = new Activity($request->all());
            $activity->save();
            return json_encode(["status" => "success","message" => "Activity added successfully."]);
        }
    }
    public function updateStatus(Request $request,$id){
        $activity = Activity::findOrFail($id);
        $activity->status = $request->status;
        $activity->save();
        return json_encode(["status" => "success","message" => "Status updated successfully"]);
    }
    public function destroy($id){
        $activity = Activity::findOrFail($id);
        $activity->delete();
        return Inertia::location(route("admin.activities"));
    }
}