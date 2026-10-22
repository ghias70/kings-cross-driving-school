<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'public_token',
        'package',
        'student_name',
        'email',
        'phone',
        'postcode',
        'test_centre',
        'notes',
        'starts_at',
        'ends_at',
        'amount_pence',
        'deposit_pence',
        'status',
        'stripe_session_id',
        'hold_expires_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'hold_expires_at' => 'datetime',
            'paid_at' => 'datetime',
            'amount_pence' => 'integer',
            'deposit_pence' => 'integer',
        ];
    }

    public function scopeClosedDiary(Builder $query): Builder
    {
        $now = now();

        return $query->where(function (Builder $query) use ($now) {
            $query->whereIn('status', ['cancelled', 'expired'])
                ->orWhere(function (Builder $query) use ($now) {
                    $query->where('status', 'pending')
                        ->where(function (Builder $query) use ($now) {
                            $query->whereNull('hold_expires_at')->orWhere('hold_expires_at', '<=', $now);
                        });
                });
        });
    }

    public function scopeBlocking(Builder $query, CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $query
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->where(function (Builder $query) {
                $query->where('status', 'paid')
                    ->orWhere(function (Builder $query) {
                        $query->where('status', 'pending')
                            ->where('hold_expires_at', '>', now());
                    });
            });
    }

    public function packageName(): string
    {
        return (string) config('school.packages.'.$this->package.'.name', $this->package);
    }

    public function priceLabel(): string
    {
        return '£'.number_format($this->amount_pence / 100, 0);
    }

    public function depositLabel(): string
    {
        return '£'.number_format($this->deposit_pence / 100, 0);
    }

    public function balancePence(): int
    {
        return max(0, (int) $this->amount_pence - (int) $this->deposit_pence);
    }

    public function balanceLabel(): string
    {
        return '£'.number_format($this->balancePence() / 100, 0);
    }

    public function onlineChargePence(): int
    {
        return (int) $this->deposit_pence;
    }

    public function invoiceNumber(): string
    {
        return 'KCDS-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function diaryStatus(): string
    {
        if ($this->status === 'paid') {
            return $this->ends_at->isFuture() ? 'Confirmed' : 'Done';
        }

        if ($this->status === 'pending' && $this->hold_expires_at?->isFuture()) {
            return 'Waiting for £10 deposit';
        }

        if ($this->status === 'cancelled') {
            return 'Cancelled';
        }

        return 'Not paid';
    }

    public function canCancelFromDiary(): bool
    {
        if ($this->status === 'paid') {
            return $this->ends_at->isFuture();
        }

        return $this->status === 'pending' && (bool) $this->hold_expires_at?->isFuture();
    }

    public function markPaid(): bool
    {
        $updated = static::query()
            ->whereKey($this->id)
            ->where('status', '!=', 'paid')
            ->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

        $this->refresh();

        return $updated === 1;
    }
}
