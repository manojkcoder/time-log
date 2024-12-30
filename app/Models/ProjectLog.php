<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Project;
use App\Models\User;

class ProjectLog extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $hidden = ["updated_at"];
    protected $appends = ["activity_name","project_name","user_name"];
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("M d, Y");
    }
    public function activity(){
        return $this->belongsTo(Activity::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function getActivityNameAttribute(){
        return $this->activity ? $this->activity->name : null;
    }
    public function getProjectNameAttribute(){
        return $this->project ? $this->project->name : null;
    }
    public function getUserNameAttribute(){
        return $this->user ? $this->user->name : null;
    }
}