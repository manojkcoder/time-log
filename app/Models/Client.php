<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Project;

class Client extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $hidden = ["updated_at"];
    protected function casts(): array{
        return ["status" => "boolean"];
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("M d, Y");
    }
    public function projects(){
        return $this->hasMany(Project::class);
    }
}