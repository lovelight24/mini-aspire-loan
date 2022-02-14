<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiValidationsTrait
 *
 * @author Prem
 */
namespace App\Traits;

trait ApiValidationsTrait
{
    //userd for validating token and header
    public function validateTokenAndHeader($request){
        //check token user id and loggedin userid
        $returnMessage = array();
        if((int)$request->user()->id !== (int)$request->input('user_id')){
            $returnMessage = array("message" => "Invalid access token");
        }
        
        if(!$request->headers->has('type')){
            $returnMessage = array("message"=>"No User Type");
        }
        return $returnMessage;
   }
}