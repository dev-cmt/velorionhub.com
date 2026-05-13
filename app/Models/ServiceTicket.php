<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_notes',
        'assigned_to',
        'visit_date',
        'assignment_notes',
        'technician_notes',
        'admin_notes',
        'approved_by',
        'approved_at',
        'status',
        'requested_by',

         // ... existing fields
        'priority',
        'visit_time',
        'estimated_hours',
        'required_tools',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'approved_at' => 'datetime',

        // ... existing casts
        'estimated_hours' => 'decimal:2',
        'required_tools' => 'array',
    ];

    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function products()
    {
        return $this->hasMany(ServiceTicketProduct::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        return $query;
    }
}