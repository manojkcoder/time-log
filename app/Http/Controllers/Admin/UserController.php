<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Users");
    }
    public function all(Request $request){
        $userId = $request->user()->id;
        $length = $request->length ? $request->length : 10;
        $items = User::where("id","!=",$userId)->orderBy("id","desc")->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items]);
    }
    public function createUpdate(Request $request){
        if($request->id){
            $user = User::findOrfail($request->id);
            $user->update($request->except("password","confirm_password"));
            return json_encode(["status" => "success","message" => "User updated successfully."]);
        }else{
            $user = new User($request->except("password","confirm_password"));
            $user->password = Hash::make($request->password);
            $user->save();
            return json_encode(["status" => "success","message" => "User added successfully."]);
        }
    }
    public function updateStatus(Request $request,$id){
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();
        return json_encode(["status" => "success","message" => "Status updated successfully"]);
    }
    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        return Inertia::location(route("admin.users"));
    }
}