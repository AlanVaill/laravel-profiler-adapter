<?php


namespace AlanVaill\LaravelProfilerAdapter\Listeners;


use AlanVaill\Profiler\ProfilerInterface;

class DbListener
{
    /** @var  ProfilerInterface */
    protected $profiler;

    /**
     * DbListener constructor.
     * @param ProfilerInterface $profiler
     */
    public function __construct(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
    }


    public function onQuery($sql, array $bindings, $time)
    {
        if(strpos($sql, 'insert into `query_logs`') === 0
        || strpos($sql, 'insert into `transaction_reports`') === 0
        || strpos($sql, 'insert into `transaction_traces`') === 0
        || strpos($sql, 'insert into `error_logs`') === 0
        ) {
            // don't report profiler inserts (infinite loop beware!)
            return;
        }
        $this->profiler->logQuery($sql, $bindings, $time);
    }
}