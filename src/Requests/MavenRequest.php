<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MavenRequest extends Request
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
        $rules = ['sort' => 'required'];

        foreach (config('maven.locales') as $locale => $name) {

            $rules['questions.'. $locale] = 'required';
            $rules['answers.'. $locale] = 'required';

        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = [
            'sort' => trans('maven.sort'),
            'draft_flags' => trans('maven.save_as_draft'),
        ];

        foreach (config('maven.locales') as $locale => $name) {

            $attributes['questions.'. $locale] = trans('maven.question');
            $attributes['answers.'. $locale] = trans('maven.answer');

        }

        return $attributes;
    }
}
