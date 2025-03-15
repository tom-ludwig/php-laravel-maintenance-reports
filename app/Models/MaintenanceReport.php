<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MaintenanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_uuid',
        'name',
        'status',
        'notes',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'id' => 'string'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
} 