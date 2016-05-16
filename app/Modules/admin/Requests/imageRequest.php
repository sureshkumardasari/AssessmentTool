<?php

namespace App\Modules\Admin\Requests;

use App\Http\Requests\Request;
use Auth;
use Illuminate\Http\JsonResponse;

class imageRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'image'=>'image|mimes:jpeg,jpg,png,gif|required|max:4096|resolution:200x200'
        ];
    }
    
    public function messages(){
        return [
            'image.resolution' => 'Photo must have minimum 200 x 200 resolution.',
            'image.image' => 'The photo must be an image.'
        ];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors) {
            return new JsonResponse($errors, 422);
    
    }
}
