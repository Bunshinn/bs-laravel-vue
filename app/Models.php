<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
    protected $table = 'user';
    protected $guarded = [];
    // 测试类
    public function  getInfo(){
        $users = self::get();
        return $users;
    }
}