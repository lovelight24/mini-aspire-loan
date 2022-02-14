<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Repository\LoanRepository;
use App\Traits\ApiValidationsTrait;

class LoanController extends BaseController
{

    use ApiValidationsTrait;

    public function __construct(LoanRepository $LoanRepository)
    {
        $this->LoanRepository = $LoanRepository;
    }

    /**
     * Function for store loan request submit by borrower
     * @param $request
     * @return int application id
     */
    public function addLoanRequest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                        'user_id' => 'required|numeric',
                        'applicant_name' => 'required|string|max:50',
                        'funding_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'interest_rate_weekly' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'tenure' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->all());
            }
            else {
                $getUser = $request->user();
                if (!empty($getUser)) {

                    $isValidate = $this->validateTokenAndHeader($request);
                    if (!empty($isValidate)) {
                        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                    }

                    $params = $request->all();
                    $applicationId = $this->LoanRepository->saveRequest($params);
                    if($applicationId) {
                        return $this->sendResponse($applicationId, 'Loan Request Successfully Sent. Application Id: '.$applicationId);
                    } else {
                        return $this->sendError('Error.', ['error' => 'Some Thing Wrong. Please Try Again']);
                    }
                } else {
                    return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                }
            }
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->sendError('Error', $ex);
        }
    }

    /**
     * Function for set loan request status as a approved by admin
     */
    public function approveLoanRequest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                        'user_id' => 'required|numeric',
                        'application_id' => 'required|numeric',
                        'approved_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->all());
            }
            
            $getUser = $request->user();
                if (!empty($getUser)) {

                    $isValidate = $this->validateTokenAndHeader($request);
                    if (!empty($isValidate)) {
                        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                    }
                    
                    if($request->header('type') != '1') {
                        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                    }
                    
                    $params = $request->all();
                    $loanRequest = $this->LoanRepository->detail($params['application_id']);
                    $success = $this->LoanRepository->approveLoanRequest($loanRequest, $params);
                    
                    return $this->sendResponse($success->toArray(), 'Loan Amount: '.$params['approved_amount'].' Successfully Approved For Application Id: '.$params['application_id']);
                } else {
                    return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                }
            
        } catch (\Exception $ex) {
            \Log::error($ex);
            return $this->sendError('Error', $ex);
        }
    }
    
    /**
     * Function for Repay Loan Amount By Borrower through Weekly EMI
     */
    public function repayLoanAmount(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'user_id' => 'required|numeric',
                        'application_id' => 'required|numeric',
                        'repay_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->all());
            }
            
            $getUser = $request->user();
                if (!empty($getUser)) {

                    $isValidate = $this->validateTokenAndHeader($request);
                    if (!empty($isValidate)) {
                        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                    }
                    
                    if($request->header('type') != '2') {
                        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                    }
                    
                    $params = $request->all();
                    $responce = $this->LoanRepository->updateRepayAmount($params);
                    if($responce){
                        return $this->sendResponse('', 'Loan Amount: '.$params['repay_amount'].' Successfully Paid For Application Id: '.$params['application_id']);
                    } else {
                        return $this->sendError('Error.', ['error' => 'Something wrong. Please try again']);
                    }
                } else {
                    return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                }
        }  catch (\Exception $ex) {
            \Log::error($ex);
            return $this->sendError('Error', $ex);
        }
    }
}
