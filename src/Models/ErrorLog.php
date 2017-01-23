<?php

namespace AlanVaill\LaravelProfilerAdapter\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    public function setTransactionId($id) {
        $this->transaction_report_id = $id;
    }

    public function setMessage($message) {
        $this->message = $message;
    }
}
