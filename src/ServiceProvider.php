<?php


namespace AlanVaill\LaravelProfilerAdapter;


use AlanVaill\LaravelProfilerAdapter\Reporters\DatabaseReporter;
use AlanVaill\LaravelProfilerAdapter\Listeners\DbListener;
use AlanVaill\LaravelProfilerAdapter\Listeners\LogListener;
use AlanVaill\LaravelProfilerAdapter\Subscribers\JobEventSubscriber;
use AlanVaill\Profiler\Profiler\NoOpProfiler;
use AlanVaill\Profiler\ProfilerInterface;
use AlanVaill\Profiler\Profiler\XdebugProfiler;
use AlanVaill\Profiler\ReporterInterface;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(\Illuminate\Routing\Router $router, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        if (!$this->app->runningInConsole()) {
            $kernel->pushMiddleware(TimingMiddleware::class);
        }
        // jobs process in web request if driver is sync
        \Event::subscribe(JobEventSubscriber::class);
        //global middleware
        /*
        $kernel->prependMiddleware(\Path\To\Your\Middleware\custom_auth::class);
        $kernel->pushMiddleware(\Path\To\Your\Middleware\custom_auth::class);
        */


        $listener = $this->app->make(DbListener::class);

        \DB::listen(function($query) use ($listener) {
            $listener->onQuery($query->sql, $query->bindings ?: [], $query->time);
        });

        $logListener = $this->app->make(LogListener::class);
        \Log::listen(function($level, $message, $context) use ($logListener) {
            $logListener->onLog($level, $message, $context);
        });




    }

    public function register()
    {
        if(extension_loaded('xdebug')) {
            $this->app->bind(ReporterInterface::class, DatabaseReporter::class);
            $this->app->singleton(ProfilerInterface::class, XdebugProfiler::class);
        } else {
            $this->app->singleton(ProfilerInterface::class, NoOpProfiler::class);
        }
    }
}