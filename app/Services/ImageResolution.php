<?php

namespace App\Services;

use Illuminate\Validation\Validator;

class ImageResolution extends Validator {

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateResolution($attribute, $value, $parameters) {
        // Image is the intervention library with integration into laravel 4/5
        // $value could look like this 1280x1024
        $dimensions = getimagesize($value);
        if ($dimensions[0] <= explode("x", $parameters[0])[0] || $dimensions[1] <= explode("x", $parameters[0])[1]) {
            return false;
        }
        return true;
//            return Image::make(\Input::file($attribute))->height() <= explode("x", $value)[0]  && Image::make(Input::file($attribute))->width() <= explode("x", $value)[1];
    }

    /**
     * validateAlphaNumSpecial | This function is used to validate the alpha numeric with some special character
     * @return boolean
     */
    public function validateAlphaNumSpecial($attribute, $value, $parameters) {
        $match = 'a-zA-Z0-9\s\.\,\-\'\?\(\)';
        if (!empty($parameters[0])) {
            $match .= $parameters[0];
        }
        return preg_match('/^[' . $match . ']+$/i', $value);
    }
    
    public function validateUserPassword($attribute, $value, $parameters) {
        $match = '';
        return preg_match('/^[' . $match . ']+$/i', $value);
    }
    

    public function validateAlphaNumeric($attribute, $value, $parameters) {
        return preg_match('/^[a-zA-Z0-9\s]+$/i', $value);
    }

    public function validateNumericOnly($attribute, $value, $parameters) {
        return preg_match('/^[0-9]+$/i', $value);
    }

    public function validateAlphaOnly($attribute, $value, $parameters) {
        return preg_match('/^[A-Za-z]+$/i', $value);
    }

    public function validateAlphaSpace($attribute, $value, $parameters) {
        return preg_match('/^[A-Za-z\s]+$/i', $value);
    }
    
    public function validateAlphaSpaceHyphen($attribute, $value, $parameters){
        return preg_match("/^$|^[a-zA-Z'\- ]+$/", $value);
    }
    
    public function validateAlphaSpaceHyphenNumber($attribute, $value, $parameters){
        return preg_match("/^$|^[a-zA-Z0-9'\- ]+$/", $value);
    }

    public function validateNumericDot($attribute, $value, $parameters) {
        return preg_match('/^[A-Za-z\s]+$/i', $value);
    }
    
    public function validateAtLeastOneUpperCase($attribute, $value, $parameters){
	return preg_match('/[A-Z]/', $value);
    }
        
    public function validateAtLeastOneLowerCase($attribute, $value, $parameters){
        return preg_match('/[a-z]/', $value);
    }
        
    public function validateAtLeastOneNumber($attribute, $value, $parameters){
        return preg_match('/[0-9]/', $value);
    }
    
    public function validateNotContains($attribute, $value, $parameters){
        foreach($parameters as $parameter){
            if(stripos($value, $parameter) !== false){
                return false;
            }
        }
        return true;
    }

    public function validateIUnique($attribute, $value, $parameters) {
        $table = $parameters[0];
        $column = $parameters[1];
        $userId = isset($parameters[2]) ? $parameters[2] : false;


        $rowCount = 0;

        if ($userId) {
            $rowCount = \DB::table($table)->where($column, 'ILIKE', $value)
                    ->where('id', '<>', $userId)
                    ->count();
        } else {
            $rowCount = \DB::table($table)->where($column, 'ILIKE', $value)->count();
        }

        return ( $rowCount === 0 );
    }

    public function validateIExists($attribute, $value, $parameters) {
        $table = $parameters[0];
        $column = $parameters[1];

        $rowCount = 0;

        $rowCount = \DB::table($table)->where($column, 'ILIKE', $value)->count();

        return ( $rowCount > 0 );
    }

    public function validateIIn($attribute, $value, $parameters) {
        return in_array(strtolower($value), array_map('strtolower', $parameters));
    }
}
