<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pointage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pointages';

    protected $fillable = [
        'employe_id',
        'date_heure',
        'type',
        'source',
        'reference',
        'terminal',
        'donnees',
        'commentaire',
        'type_scan',
        'qr_code_id',
        'est_anomalie',
        'description_anomalie',
    ];

    protected function casts(): array
    {
        return [
            'date_heure' => 'datetime',
            'donnees' => 'array',
            'est_anomalie' => 'boolean',
        ];
    }

    /**
     * Employé concerné par le pointage.
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    /**
     * QR Code utilisé pour le pointage.
     */
    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class, 'qr_code_id');
    }
}
