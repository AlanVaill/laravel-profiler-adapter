<?php


namespace AlanVaill\LaravelProfilerAdapter\Models;


use Illuminate\Database\Eloquent\Model;

class TransactionReport extends Model
{
    protected $primaryKey = 'transaction_report_id';
    public $incrementing = false;

    public function setTransactionReportId($transactionId)
    {
        $this->transaction_report_id = $transactionId;
    }

    public function getTransactionReportId()
    {
        return $this->transaction_report_id;
    }

    public function setElapsedTime($transactionTime)
    {
        $this->elapsed_time = $transactionTime;
    }

    public function trace()
    {
        return $this->hasOne(TransactionTrace::class, 'transaction_report_id');
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
}