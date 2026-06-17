<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostTicketUpdateRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'text' => [
                'required',
                'string',
            ]
        ];
    }
}
