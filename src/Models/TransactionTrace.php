<?php


namespace AlanVaill\LaravelProfilerAdapter\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionTrace extends Model
{
    protected $primaryKey = 'transaction_report_id';

    public function setTransactionReportId($id)
    {
        $this->transaction_report_id = $id;
    }

    public function setTrace($trace) {
        $this->trace = $trace;
    }

    /**
     * Get the user that owns the phone.
     */
    public function transactionReport()
    {
        return $this->belongsTo(TransactionReport::class);
    }
}