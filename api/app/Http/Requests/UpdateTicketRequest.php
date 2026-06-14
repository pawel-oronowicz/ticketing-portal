<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(TicketStatus::cases()),
            ],
            'priority' => [
                'required',
                'string',
                Rule::in(TicketPriority::cases()),
            ],
            'assigned_user_id' => 'integer|exists:users,id',
        ];
    }
}
