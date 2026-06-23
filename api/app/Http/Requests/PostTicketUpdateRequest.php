<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostTicketUpdateRequest extends FormRequest
{
    // @codeCoverageIgnoreStart

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'text' => [
                'required',
                'string',
            ],
            'is_internal' => [
                'boolean',
            ]
        ];
    }

    // @codeCoverageIgnoreEnd
}
