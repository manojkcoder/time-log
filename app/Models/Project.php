<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Client;

class Project extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $hidden = ["updated_at"];
    protected $appends = ["client_name"];
    protected function casts(): array{
        return ["status" => "boolean"];
    }
    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format("M d, Y");
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function getClientNameAttribute(){
        return $this->client ? $this->client->name : null;
    }
}