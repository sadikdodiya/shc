<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'description',
        'category',
        'unit',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'minimum_stock',
        'status',
        'notes'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'status' => 'boolean',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_stock_quantity',
        'formatted_minimum_stock',
        'stock_status',
    ];

    // Relationships
    /**
     * Get the company that owns the item.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all part entries for the item.
     */
    public function partEntries()
    {
        return $this->hasMany(PartEntry::class)->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }
    
    /**
     * Get the recent part entries for the item.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentPartEntries($limit = 10)
    {
        return $this->partEntries()->take($limit);
    }

    /**
     * Get the formatted stock quantity.
     *
     * @return string
     */
    public function getFormattedStockQuantityAttribute()
    {
        return number_format($this->stock_quantity, 2) . ' ' . $this->unit;
    }

    /**
     * Get the formatted minimum stock level.
     *
     * @return string
     */
    public function getFormattedMinimumStockAttribute()
    {
        if (is_null($this->minimum_stock)) {
            return 'N/A';
        }
        return number_format($this->minimum_stock, 2) . ' ' . $this->unit;
    }

    /**
     * Check if the item is low on stock.
     *
     * @return bool
     */
    public function isLowOnStock()
    {
        if (is_null($this->minimum_stock) || $this->minimum_stock <= 0) {
            return false;
        }
        return (float) $this->stock_quantity <= (float) $this->minimum_stock;
    }
    
    /**
     * Check if the item is out of stock.
     *
     * @return bool
     */
    public function isOutOfStock()
    {
        return (float) $this->stock_quantity <= 0;
    }

    /**
     * Get the stock status.
     *
     * @return array
     */
    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return [
                'text' => 'Out of Stock',
                'color' => 'bg-red-100 text-red-800',
                'icon' => 'times-circle',
                'status' => 'out_of_stock',
            ];
        }
        
        if ($this->isLowOnStock()) {
            return [
                'text' => 'Low Stock',
                'color' => 'bg-yellow-100 text-yellow-800',
                'icon' => 'exclamation-triangle',
                'status' => 'low_stock',
            ];
        }

        return [
            'text' => 'In Stock',
            'color' => 'bg-green-100 text-green-800',
            'icon' => 'check-circle',
            'status' => 'in_stock',
        ];
    }

    /**
     * Update the stock quantity based on part entries.
     *
     * @return bool
     */
    public function updateStockFromEntries(): bool
    {
        $result = $this->partEntries()
            ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as total')
            ->value('total');
            
        $newStock = (float) ($result ?? 0);
        
        // Only update if the stock has changed to prevent unnecessary updates
        if ((float) $this->stock_quantity !== $newStock) {
            return $this->update(['stock_quantity' => $newStock]);
        }
        
        return true;
    }
    
    /**
     * Add stock to the item.
     *
     * @param  float  $quantity
     * @param  string  $reference
     * @param  string  $notes
     * @param  int|null  $userId
     * @return \App\Models\PartEntry
     */
    public function addStock(float $quantity, string $reference = null, string $notes = null, int $userId = null)
    {
        return $this->partEntries()->create([
            'type' => 'in',
            'quantity' => $quantity,
            'reference' => $reference,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }
    
    /**
     * Remove stock from the item.
     *
     * @param  float  $quantity
     * @param  string  $reference
     * @param  string  $notes
     * @param  int|null  $userId
     * @return \App\Models\PartEntry
     * @throws \Exception
     */
    public function removeStock(float $quantity, string $reference = null, string $notes = null, int $userId = null)
    {
        if ($this->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock. Available: {$this->stock_quantity}");
        }
        
        return $this->partEntries()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'reference' => $reference,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }
    
    /**
     * Adjust stock to a specific quantity.
     *
     * @param  float  $newQuantity
     * @param  string  $reference
     * @param  string  $notes
     * @param  int|null  $userId
     * @return \App\Models\PartEntry
     */
    public function adjustStockTo(float $newQuantity, string $reference = null, string $notes = null, int $userId = null)
    {
        $difference = $newQuantity - $this->stock_quantity;
        
        if ($difference > 0) {
            return $this->addStock($difference, $reference, $notes, $userId);
        } elseif ($difference < 0) {
            return $this->removeStock(abs($difference), $reference, $notes, $userId);
        }
        
        // If no change, just return a new part entry with 0 quantity
        return $this->partEntries()->make([
            'type' => 'in',
            'quantity' => 0,
            'reference' => $reference,
            'notes' => $notes ?: 'No stock adjustment needed',
            'user_id' => $userId ?? auth()->id(),
        ]);
    }


    // Scopes
    /**
     * Scope a query to only include active items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }
    
    /**
     * Scope a query to only include inactive items.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    /**
     * Scope a query to only include items that are low on stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowOnStock(Builder $query): Builder
    {
        return $query->whereRaw('stock_quantity <= minimum_stock AND minimum_stock > 0');
    }
    
    /**
     * Scope a query to only include items that are out of stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    /**
     * Scope a query to only include items of a specific category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
    
    /**
     * Scope a query to only include items with stock below the minimum threshold.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $this->scopeLowOnStock($query);
    }

    /**
     * Scope a query to only include items for a specific company.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $companyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }
    
    /**
     * Scope a query to search items by name or code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    // Model Events
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // When an item is created, set default values
        static::creating(function ($item) {
            if (is_null($item->stock_quantity)) {
                $item->stock_quantity = 0;
            }
            
            if (is_null($item->status)) {
                $item->status = true; // Active by default
            }
        });
        
        // When an item is updated, ensure stock doesn't go negative
        static::updating(function ($item) {
            if ($item->isDirty('stock_quantity') && $item->stock_quantity < 0) {
                $item->stock_quantity = 0;
            }
        });
    }
    
    // Helpers
    /**
     * Check if the item is low on stock.
     *
     * @return bool
     */
    public function isLowStock(): bool
    {
        return $this->isLowOnStock();
    }
    
    /**
     * Get the stock level as a percentage of the minimum stock.
     *
     * @return float
     */
    public function getStockLevelPercentage(): float
    {
        if ($this->minimum_stock <= 0) {
            return $this->stock_quantity > 0 ? 100 : 0;
        }
        
        $percentage = ($this->stock_quantity / $this->minimum_stock) * 100;
        return min(100, max(0, $percentage)); // Ensure between 0 and 100
    }
    
    /**
     * Get the stock status class for UI display.
     *
     * @return string
     */
    public function getStockStatusClass(): string
    {
        if ($this->isOutOfStock()) {
            return 'bg-red-100 text-red-800';
        }
        
        if ($this->isLowOnStock()) {
            return 'bg-yellow-100 text-yellow-800';
        }
        
        return 'bg-green-100 text-green-800';
    }
    
    /**
     * Get the stock status icon for UI display.
     *
     * @return string
     */
    public function getStockStatusIcon(): string
    {
        if ($this->isOutOfStock()) {
            return 'times-circle';
        }
        
        if ($this->isLowOnStock()) {
            return 'exclamation-triangle';
        }
        
        return 'check-circle';
    }
    
    /**
     * Update stock quantity directly (use with caution).
     *
     * @param  float  $quantity
     * @param  string  $type  'in' or 'out'
     * @return bool
     */
    public function updateStock(float $quantity, string $type = 'in'): bool
    {
        if (!in_array($type, ['in', 'out'])) {
            throw new \InvalidArgumentException("Type must be either 'in' or 'out'");
        }
        
        $newQuantity = $type === 'in' 
            ? $this->stock_quantity + $quantity 
            : $this->stock_quantity - $quantity;
            
        // Ensure stock doesn't go negative
        $newQuantity = max(0, $newQuantity);
            
        return $this->update(['stock_quantity' => $newQuantity]);
    }
    
    /**
     * Get a summary of stock movements for a given period.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getStockMovementSummary(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): array
    {
        $entries = $this->partEntries()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('type, SUM(quantity) as total_quantity, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('total_quantity', 'type')
            ->toArray();
            
        return [
            'in' => (float) ($entries['in'] ?? 0),
            'out' => (float) ($entries['out'] ?? 0),
            'net' => ($entries['in'] ?? 0) - ($entries['out'] ?? 0),
            'start_quantity' => $this->getStockAtDate($startDate),
            'end_quantity' => $this->getStockAtDate($endDate),
        ];
    }
    
    /**
     * Get the stock quantity at a specific date.
     *
     * @param  \Carbon\Carbon  $date
     * @return float
     */
    public function getStockAtDate(\Carbon\Carbon $date): float
    {
        $initialStock = $this->created_at > $date ? 0 : $this->stock_quantity;
        
        $entries = $this->partEntries()
            ->where('created_at', '<=', $date)
            ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as total')
            ->value('total');
            
        return (float) ($entries ?? 0);
    }
}
