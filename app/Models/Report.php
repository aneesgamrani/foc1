<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    public const AUDIENCE_DEVELOPER = 'developer';
    public const AUDIENCE_ENTERPRISE = 'enterprise';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';

    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_QUARTERLY = 'quarterly';
    public const TYPE_BIANNUAL = 'biannual';
    public const TYPE_ANNUAL = 'annual';

    public const TYPES = [
        self::TYPE_MONTHLY,
        self::TYPE_QUARTERLY,
        self::TYPE_BIANNUAL,
        self::TYPE_ANNUAL,
    ];

    public const AUDIENCES = [
        self::AUDIENCE_DEVELOPER,
        self::AUDIENCE_ENTERPRISE,
    ];

    protected $fillable = [
        'user_id',
        'audience',
        'report_type',
        'report_month',
        'report_quarter',
        'biannual_half',
        'report_year',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'report_month' => 'integer',
            'report_quarter' => 'integer',
            'biannual_half' => 'integer',
            'report_year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ReportSection::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ReportEvent::class)->latest('id');
    }

    public function periodLabel(): string
    {
        return match ($this->report_type) {
            self::TYPE_MONTHLY => sprintf('%s-%d', now()->setMonth((int) $this->report_month)->shortMonthName, $this->report_year),
            self::TYPE_QUARTERLY => sprintf('Q%d-%d', $this->report_quarter, $this->report_year),
            self::TYPE_BIANNUAL => sprintf('%s-%d', $this->biannual_half === 1 ? 'H1' : 'H2', $this->report_year),
            default => (string) $this->report_year,
        };
    }
}
