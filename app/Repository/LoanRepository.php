<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Models\FundRequests;
use App\Models\FundRepayments;

/**
 * Description of LoanRepository
 *
 * @author Prem
 */
class LoanRepository
{

    private $fundRequestModel, $fundRepayModel;

    public function __construct(FundRequests $fundRequestModel, FundRepayments $fundRepayModel)
    {
        $this->fundRequestModel = $fundRequestModel;
        $this->fundRepayModel = $fundRepayModel;
    }

    /**
     * Function for save Loan request in fund_requests table.
     */
    public function saveRequest($params)
    {
        try {
            $applicationData = $this->fundRequestModel->create($params);
            return $applicationData->id;
        } catch (\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }
    
    
    /**
     * To Loan detail
     * 
     * @param integer $id
     * @return Loan Request
     * @throws \Exception
     */
    public function detail($id)
    {
        try {
            $loanRequest = $this->fundRequestModel->find($id);
            return $loanRequest;
        } catch (\Exception $ex) {
            \Log::error($ex);
            throw new \Exception("Unable to get the loan request");
        }
    }
    

    /**
     * Function for set Loan Request Status as a Approved
     *
     */
    public function approveLoanRequest($loanReq, $params)
    {
        try {
            $loanReq->status = config('constants.loan_status.approved');
            $loanReq->disbursed_amount = $params['approved_amount'];
            $loanReq->disbursed_date = date("Y-m-d");
            $result = $loanReq->save();
            if($result) {
                $this->prepareRepaymentTable($loanReq);
                return $this->getRepayDetails($loanReq->id);
            } else {
                return false;
            }
            
        } catch (\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }
    
    
    /**
     * Function for Calculate Repayment schedule and stored in DB.
     */
    public function prepareRepaymentTable($loanReq) {
        try {
            $principleAmount = $loanReq->disbursed_amount;
            $interestRate = $loanReq->interest_rate_weekly;
            $tenure = $loanReq->tenure;
            
            $weeklyEMI = ($principleAmount * $interestRate * pow(1 + $interestRate, $tenure)) /(pow(1 + $interestRate, $tenure) - 1);
            $rePaymentDate = $loanReq->disbursed_date;
            for($i = 1; $i<=$tenure; $i++) {
                $rePaymentDate =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($rePaymentDate)) . " +1 week"));
                $this->fundRepayModel->create([
                    'fund_request_id' => $loanReq->id, 
                    'repayment_date' => $rePaymentDate, 
                    'repayment_amount' => $weeklyEMI,
                ]);
            }
            
        }  catch (\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }
    
    
    /**
     * Function for Get All Repay Details of Loan Request via loan request ID
     */
    public function getRepayDetails($loanId) {
        try {
            return $this->fundRepayModel->where('fund_request_id', $loanId)->get();
        }  catch (\Exception $ex) {
            \Log::error($ex);
            throw new \Exception("Unable to fetch Repayment details");
        }
    }
    
    /**
     * Function for Update Repay Amount in fund_repayments Table
     */
    public function updateRepayAmount($params) {
        try {
            $loanId = $params['application_id'];
            $submitAmount = $params['repay_amount'];
            
            $repayDetail = $this->fundRepayModel->where('fund_request_id', $loanId)->where('is_paid', 0)->orderBy('repayment_date', 'ASC')->first();
            if($repayDetail) {
                $repayDetail->is_paid = 1;
                $repayDetail->paid_date = date("Y-m-d");
                $repayDetail->save();
                return true;
            } else {
                return false;
            }
        } catch (\Exception $ex) {
            \Log::error($ex);
            throw new \Exception("Unable to fetch Repayment details");
        }
    }
}
