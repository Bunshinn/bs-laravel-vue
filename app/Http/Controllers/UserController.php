<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Routing\Controller;
class UserController extends Controller
{
    public function __construct()
    {
        $this->objUser = new User();
    }
    public function getUserList(){
        return $this->objUser->getInfo();
    }
}