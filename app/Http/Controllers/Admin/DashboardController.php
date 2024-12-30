<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): Response{
        return Inertia::render('Admin/Dashboard');
    }
    public function ChangePassword(): Response{
        return Inertia::render('Admin/ChangePassword');
    }
    public function updatePassword(Request $request){
        $user = $request->user();
        if(!Hash::check($request->old_password,$user->password)) {
            return json_encode(["status" => "error","message" => "Old password is incorrect."]);
        }
        if($request->old_password === $request->new_password){
            return json_encode(["status" => "error","message" => "The new password cannot be the same as the old password."]);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return json_encode(["status" => "success","message" => "Password updated successfully."]);
    }
}