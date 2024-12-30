<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void{
        foreach($this->users() as $user){
            User::updateOrCreate(['email' => $user['email']],$user);
        }
    }
    private function users(){
        return [
            ['name' => 'Administration','email' => 'admin@gmail.com','password' => Hash::make('Test@123'),'role' => 'admin'],
            ['name' => 'Pradeep Kumar','email' => 'pradeep@32bitsolutions.com','password' => Hash::make('Test@123'),'role' => 'user'],
            ['name' => 'Raman Deep Bajwa','email' => 'ramandeep.bajwa@32bitsolutions.com','password' => Hash::make('Test@123'),'role' => 'user']
        ];
    }
}