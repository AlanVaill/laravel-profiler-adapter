<?php


namespace AlanVaill\LaravelProfilerAdapter;

use AlanVaill\Profiler\ProfilerInterface;
use Closure;
use Illuminate\Http\Request;

class TimingMiddleware
{
    /**
     * @var ProfilerInterface
     */
    protected $profiler;

    /**
     * TimingMiddleware constructor.
     * @param ProfilerInterface $profiler
     */
    public function __construct(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
    }


    public function handle(Request $request, Closure $next)
    {
        // before action
        $this->profiler->begin();

        $response = $next($request);

        // retrieve information about the dispatched action
        $route = \Route::getCurrentRoute();
        if($route) {
            $transactionName = $route->getActionName();
        } else {
            $transactionName = 'unknown';
        }

        $this->profiler->end($transactionName);

        return $response;
    }
}