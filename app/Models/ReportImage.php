<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'maintenance_report_id',
        'name',
        'path'
    ];

    public function maintenanceReport()
    {
        return $this->belongsTo(MaintenanceReport::class);
    }
}
