<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Clients");
    }
    public function all(Request $request){
        $length = $request->length ? $request->length : 10;
        $items = Client::orderBy("id","desc")->paginate($length)->onEachSide(1);
        return json_encode(["items" => $items]);
    }
    public function createUpdate(Request $request){
        if($request->id){
            $client = Client::findOrfail($request->id);
            $client->update($request->all());
            return json_encode(["status" => "success","message" => "Client updated successfully."]);
        }else{
            $client = new Client($request->all());
            $client->save();
            return json_encode(["status" => "success","message" => "Client added successfully."]);
        }
    }
    public function updateStatus(Request $request,$id){
        $client = Client::findOrFail($id);
        $client->status = $request->status;
        $client->save();
        return json_encode(["status" => "success","message" => "Status updated successfully"]);
    }
    public function destroy($id){
        $client = Client::findOrFail($id);
        $client->delete();
        return Inertia::location(route("admin.clients"));
    }
}
