<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MaintenanceReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'maintenance_reports';

    protected $fillable = [
        'id',
        'project_id',
        'name',
        'status',
        'notes'
    ];

    protected $casts = [
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->hasMany(ReportImage::class, 'maintenance_report_id');
    }
} 