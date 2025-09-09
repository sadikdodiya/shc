<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PartEntry extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'type',
        'quantity',
        'reference',
        'notes',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_quantity',
        'formatted_date',
    ];

    /**
     * Get the item that owns the part entry.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the user that created the part entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'System',
        ]);
    }

    /**
     * Scope a query to only include entries of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include entries for a specific item.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $itemId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForItem(Builder $query, int $itemId): Builder
    {
        return $query->where('item_id', $itemId);
    }

    /**
     * Scope a query to only include recent entries.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the formatted quantity with sign based on entry type.
     *
     * @return string
     */
    public function getFormattedQuantityAttribute(): string
    {
        $sign = $this->type === 'in' ? '+' : '-';
        return $sign . ' ' . number_format($this->quantity, 2);
    }

    /**
     * Get the formatted created at date.
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Get the previous entry for the same item.
     *
     * @return \App\Models\PartEntry|null
     */
    public function getPreviousEntry()
    {
        return $this->item->partEntries()
            ->where('id', '<', $this->id)
            ->latest('id')
            ->first();
    }

    /**
     * Get the next entry for the same item.
     *
     * @return \App\Models\PartEntry|null
     */
    public function getNextEntry()
    {
        return $this->item->partEntries()
            ->where('id', '>', $this->id)
            ->oldest('id')
            ->first();
    }

    /**
     * Get the stock level after this entry was applied.
     *
     * @return float
     */
    public function getStockAfterEntry(): float
    {
        return $this->item->partEntries()
            ->where('created_at', '<=', $this->created_at)
            ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as total')
            ->value('total') ?? 0;
    }

    /**
     * Get the stock level before this entry was applied.
     *
     * @return float
     */
    public function getStockBeforeEntry(): float
    {
        return $this->getStockAfterEntry() - ($this->type === 'in' ? $this->quantity : -$this->quantity);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // When a part entry is created or updated, update the item's stock
        static::saved(function ($entry) {
            $entry->item->updateStockFromEntries();
        });

        // When a part entry is deleted, update the item's stock
        static::deleted(function ($entry) {
            $entry->item->updateStockFromEntries();
        });
    }
}
