<?php


namespace AlanVaill\LaravelProfilerAdapter\Subscribers;


use AlanVaill\Profiler\ProfilerInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class JobEventSubscriber
{
    /** @var  ProfilerInterface */
    protected $profiler;

    /**
     * JobEventSubscriber constructor.
     * @param ProfilerInterface $profiler
     */
    public function __construct(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
    }


    public function onBeforeJob($event)
    {
        $this->profiler->begin();
    }

    public function onAfterJob(JobProcessed $event)
    {
        if(method_exists($event->job, 'resolveName')) {
            $name = $event->job->resolveName();
        } else {
            $name = $event->job->getName();
        }
        \Log::error("After job ". $name);
        $this->profiler->end($name);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        \Log::error('subscribing ' . JobEventSubscriber::class . '@onBeforeJob');
        $events->listen(
            JobProcessing::class,
            JobEventSubscriber::class . '@onBeforeJob'
        );

        $events->listen(
            JobProcessed::class,
            JobEventSubscriber::class . '@onAfterJob'
        );
    }
}