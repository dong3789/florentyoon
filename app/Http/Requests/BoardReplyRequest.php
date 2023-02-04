<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoardReplyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'reply_content' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'regex' => ':attribute은(는) 값 확인이 필요합니다.(1 이상)'
        ];
    }

    public function attributes(){
        return [
            'reply_content' => '내용',
        ];
    }
}
