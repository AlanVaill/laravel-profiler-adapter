<?php


namespace AlanVaill\LaravelProfilerAdapter\Models;


use Illuminate\Database\Eloquent\Model;

class QueryLog extends Model
{
    public function setTransactionId($transactionId) {
        $this->transaction_report_id = $transactionId;
    }

    public function setSql($sql) {
        $this->sql = $sql;
        $this->hash = md5($sql);
    }

    public function setBindings(array $bindings) {
        $this->bindings = json_encode($bindings);
    }

    public function setExecutionTime($time) {
        $this->execution_time = $time;
    }
}