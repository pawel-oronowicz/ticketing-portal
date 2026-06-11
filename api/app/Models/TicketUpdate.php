<?php

namespace App\Models;

use Database\Factories\TicketUpdateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketUpdate extends Model
{
    protected $fillable = ['text', 'is_internal'];

    /** @use HasFactory<TicketUpdateFactory> */
    use HasFactory;

    public function ticket(): BelongsTo
    {
        return $this->BelongsTo(Ticket::class);
    }
}
