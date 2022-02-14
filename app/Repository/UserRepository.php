<?php
namespace App\Repository;

use App\Models\User;
use App\Helper;
use Validator;
use Auth;
use Carbon;

/**
 * All user manage methods goes here
 */
class UserRepository
{
    private $userModel;
    
    public function __construct(User $userModel)
    {
        $this->userModel               = $userModel;
    }
    
    public function userLogin($request)
    {
        try {
            
            Auth::attempt(['email' => $request->email, 'password' => $request->password]);
            $user = \Auth::user(); 
            if(!empty($user)) {
                $userDetail['id'] = $user->id;
                $userDetail['name'] =  $user->name;
                $userDetail['type'] = $user->type;
                $userDetail['token'] =  'Bearer '.$user->createToken($request->email)->accessToken; 
                \Auth::user()->setAttribute('token',$userDetail['token']);
                return $userDetail;
            } else {
                return false;
            }
        } catch(\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }
}
