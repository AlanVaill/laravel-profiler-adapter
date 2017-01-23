<?php


namespace AlanVaill\LaravelProfilerAdapter\Reporters;


use AlanVaill\LaravelProfilerAdapter\Models\ErrorLog;
use AlanVaill\LaravelProfilerAdapter\Models\QueryLog;
use AlanVaill\LaravelProfilerAdapter\Models\TransactionReport;
use AlanVaill\LaravelProfilerAdapter\Models\TransactionTrace;
use AlanVaill\Profiler\ReporterInterface;
use Illuminate\Database\QueryException;

class DatabaseReporter implements ReporterInterface
{
    /**
     * @param string $id unique identifier of transaction
     * @param string $label identify type of transaction (usually controller action)
     * @param float $time elapsed time in seconds of transactions
     * @param string $traceFile path to trace
     */
    public function report($id, $label, $time, $traceFile)
    {
        try {
            $trace = new TransactionTrace();
            $trace->setTrace(file_get_contents($traceFile));

            $model = new TransactionReport();
            $model->setTransactionReportId($id);
            $model->setElapsedTime($time);
            $model->setLabel($label);
            $model->trace()->save($trace);
            $model->save();
        } catch (QueryException $e) {
            // chicken before egg -- trying to save query log before we've run migration for it
            // we can ignore table not found exception
            if($e->getCode() != '42S02'){
                throw $e;
            }
            //\Log::info('Could not save transaction report: ' . $e->getMessage());
        }
    }

    public function reportQueryExecution($sql, $bindings, $time, $profileId = null)
    {
        try {
            $model = new QueryLog();
            $model->setSql($sql);
            $model->setBindings($bindings);
            $model->setExecutionTime($time);
            $model->setTransactionId($profileId);
            $model->save();
        } catch (QueryException $e) {
            // chicken before egg -- trying to save query log before we've run migration for it
            // we can ignore this exception
            if($e->getCode() != '42S02'){
                throw $e;
            }
            //\Log::info('Could not save query log: ' . $e->getMessage());
        }
    }

    public function reportError($message, $profileId = null)
    {
        try {
            $model = new ErrorLog();
            $model->setMessage($message);
            $model->setTransactionId($profileId);
            $model->save();
        } catch (QueryException $e) {
            // chicken before egg -- trying to save query log before we've run migration for it
            // we can ignore this exception
            if($e->getCode() != '42S02'){
                throw $e;
            }
            //\Log::info('Could not save query log: ' . $e->getMessage());
        }
    }
}