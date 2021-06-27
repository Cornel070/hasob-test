<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AssetAssignment extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'asset_id', 'assignment_date', 'status',
        'is_due', 'due_date', 'assigned_user_id',
        'assigned_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
