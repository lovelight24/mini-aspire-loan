<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Repository\UserRepository;

class LoginController extends BaseController
{

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                        'email' => 'required|email',
                        'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }


            $success = $this->UserRepository->userLogin($request);
            if ($success) {
                return $this->sendResponse($success, 'Login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->sendError('Error', $ex);
        }
    }

}
