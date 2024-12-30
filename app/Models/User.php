<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class User extends Authenticatable
{
    use SoftDeletes;
    protected $guarded  = [];
    protected $hidden = ["password","remember_token"];
    protected function casts(): array{
        return ["password" => "hashed","status" => "boolean"];
    }
    public function scopeUser($query){
        return $query->where("role","user");
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("d M Y");
    }
}