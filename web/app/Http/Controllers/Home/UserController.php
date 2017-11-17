<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 17/11/17
 * Time: 上午11:10
 */

namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Http\Models\User;
use App\Http\Util\DateTime;
use App\Http\Util\Salt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function createUser(Request $request){
        $pass = $request->input('pass');
        if ($pass != 'ASIi03nc9DFSN044jf0FDSFJkfk09342KCv0jvf0j'){
            return response('');
        }

        $password = Salt::generate(8);
        $user_name = Salt::generate(3);
        $user_id = Salt::generate(10);

        $user = new User();
        $user->user_id = $user_id;
        $user->nick = $user_name;
        $user->password = crypt($password, Salt::generateSha256());
        $user->email = '';
        $user->submit = 0;
        $user->solved = 0;
        $user->defunct = 'N';
        $user->ip = '127.0.0.1';
        $user->accesstime = DateTime::format();
        $user->volume = 1;
        $user->language = 1;
        $user->reg_time = DateTime::format();
        $user->school = 'Sanjieke';
        $user->save();

        return response(json_encode(array(
            "password" => $password,
            "user_name" => $user_name
        )));
    }

    public function login(Request $request){
        $user_id = $request->input('user_id');
        $password = $request->input('password');

        $user = User::find($user_id);
        if (null == $user){
            return response('not exist or error');
        }

        if (hash_equals($user->password, crypt($password, $user->password))){
            $request->session()->put('logged', true);
            $request->session()->put('user_id', $user->user_id);
            return response('success');
        } else{
            return response('not exist or error');
        }
    }
}