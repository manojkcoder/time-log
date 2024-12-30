<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run(): void{
        foreach($this->activities() as $activity){
            Activity::updateOrCreate($activity,$activity);
        }
    }
    private function activities(){
        return [
            ['name' => 'Web Development'],
            ['name' => 'APP Development'],
            ['name' => 'Frontend Development'],
            ['name' => 'PSD to HTML'],
            ['name' => 'Company Internal Work'],
            ['name' => 'Others'],
            ['name' => 'PSD Design'],
            ['name' => 'Search Engine Updates'],
            ['name' => 'SEO Audit'],
            ['name' => 'SEO Link Building'],
            ['name' => 'SEO (On Page)'],
            ['name' => 'SEO (Off Page)']
        ];
    }
}