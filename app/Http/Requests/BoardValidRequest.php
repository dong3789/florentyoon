<?php

namespace App\Http\Requests;

use App\Models\CatUsers;
use Illuminate\Foundation\Http\FormRequest;
use function App\responseData;

class BoardValidRequest extends FormRequest
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
            'title' => ['required'],
            'content' => ['required'],
            'category_type_id' => ['required', 'regex:/[1-9]/']
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'min' => ':attribute은(는) 최소 :min 글자 이상이 필요합니다.',
            'regex' => ':attribute은(는) 값 확인이 필요합니다.(1 이상)'
        ];
    }

    public function attributes(){
        return [
            'title' => '제목',
            'content' => '내용',
            'category_type_id' => '질문 타입'
        ];
    }

}
