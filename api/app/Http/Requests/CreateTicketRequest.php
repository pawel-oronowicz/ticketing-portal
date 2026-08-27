<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTicketRequest extends FormRequest
{
    // @codeCoverageIgnoreStart

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string',
            'description' => 'required|string',
            'company_id' => 'required|integer|exists:companies,id',
            'site_id' => 'required|integer|exists:sites,id',
            'assigned_user_id' => 'nullable|integer|exists:users,id',
            'priority' => [
                'required',
                'string',
                Rule::in(TicketPriority::cases()),
            ],
        ];
    }

    // @codeCoverageIgnoreEnd
}
