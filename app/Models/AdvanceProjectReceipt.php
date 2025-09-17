<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceProjectReceipt extends Model
{
    protected $fillable = [
        'client_id',
        'project_id',
        'reference_no',
        'receive_amount',
        'payment_method',
        'ledger_id',
        'payment_date',
        'bank_account_no',
        'cheque_no',
        'cheque_date',
        'bank_batch_no',
        'bank_date',
        'bkash_number',
        'bkash_date',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
