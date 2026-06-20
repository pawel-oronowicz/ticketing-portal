<?php

namespace App\Models;

use Database\Factories\TicketUpdateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketUpdate extends Model
{
    // @codeCoverageIgnoreStart

    protected $fillable = ['text', 'is_internal'];

    /** @use HasFactory<TicketUpdateFactory> */
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->BelongsTo(Ticket::class);
    }

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'created_by_user_id');
    }

    // @codeCoverageIgnoreEnd
}
