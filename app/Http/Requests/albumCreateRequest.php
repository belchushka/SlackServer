<?php

namespace App\Http\Requests;

use App\Http\Resources\ErrorResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class albumCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "icon"=>['required','mimes:img,jpg,jpeg,png'],
            "name"=>['required','min:5'],
            "songs"=>['required', 'array'],
            "songs.*.name"=>['required','min:5'],
            "songs.*.file"=>['required','mimes:mp3,wav,flac']
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(new ErrorResource($validator->errors()),400));
    }
}
