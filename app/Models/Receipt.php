<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $guarded = [];

    // Relationship to Ledger model (one-to-many)
    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    // Relationship to IncomingChalan model (one-to-many)
    public function incomingChalan()
    {
        return $this->belongsTo(IncomingChalan::class);
    }

    // Relationship to OutcomingChalan model (one-to-many)
    public function outcomingChalan()
    {
        return $this->belongsTo(OutcomingChalan::class);
    }

    // Accessor for getting status as a human-readable format (optional)
    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    // Mutator for setting receive_mode to lowercase (optional)
    public function setReceiveModeAttribute($value)
    {
        $this->attributes['receive_mode'] = strtolower($value);
    }

    // Example scope to filter by status (optional)
    public function scopeIncoming($query)
    {
        return $query->where('status', 'incoming');
    }

    public function scopeOutcoming($query)
    {
        return $query->where('status', 'outcoming');
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
