<?php


namespace AlanVaill\LaravelProfilerAdapter\Listeners;


use AlanVaill\Profiler\ProfilerInterface;

class LogListener
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


    public function onLog($level, $message)
    {
        if($level === 'error') {
            $this->profiler->logError($message);
        }
    }
}