<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Get the user who made the report.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the user who was reported.
     */
    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    /**
     * Get the admin who reviewed the report.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the reportable item (Photo, Message, etc).
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if report is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if report is being reviewed.
     */
    public function isReviewing(): bool
    {
        return $this->status === 'reviewing';
    }

    /**
     * Check if report is resolved.
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Check if report is dismissed.
     */
    public function isDismissed(): bool
    {
        return $this->status === 'dismissed';
    }

    /**
     * Mark report as reviewing.
     */
    public function markAsReviewing(User $admin): void
    {
        $this->update([
            'status' => 'reviewing',
            'reviewed_by' => $admin->id,
        ]);
    }

    /**
     * Mark report as resolved.
     */
    public function markAsResolved(User $admin, ?string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'reviewed_by' => $admin->id,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Mark report as dismissed.
     */
    public function markAsDismissed(User $admin, ?string $notes = null): void
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $admin->id,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Scope to get pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get reviewing reports.
     */
    public function scopeReviewing($query)
    {
        return $query->where('status', 'reviewing');
    }

    /**
     * Scope to get resolved reports.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope to get dismissed reports.
     */
    public function scopeDismissed($query)
    {
        return $query->where('status', 'dismissed');
    }

    /**
     * Scope to get reports by reason.
     */
    public function scopeByReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }

    /**
     * Get reason label.
     */
    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'fake_profile' => 'Fake Profile',
            'inappropriate_content' => 'Inappropriate Content',
            'harassment' => 'Harassment',
            'scam' => 'Scam/Fraud',
            'spam' => 'Spam',
            'other' => 'Other',
            default => ucwords(str_replace('_', ' ', $this->reason)),
        };
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'reviewing' => 'blue',
            'resolved' => 'green',
            'dismissed' => 'gray',
            default => 'gray',
        };
    }
}
