<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Module;
use Cache;
use Storage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // 3 days
        $minutes = (1440 * 3);

        $this->listen = Cache::remember('event_listeners', $minutes, function() {
            // build listen list
            $modules = Module::all();

            // array to return, containaing
            // keys of event class paths and
            // listener class paths
            $listen = [];

            // aray of events
            $events = [];

            // array of listeners
            $listeners = [];
            foreach($modules as $module) {
                // find all events
                $files = Storage::disk('plugins')->files(ucfirst($module['slug']) . '/Events');

                if (!empty($files)) {
                    foreach($files as $file) {
                        $path = str_replace('/', '\/', $file);

                        $class = basename($file);
                        $class = str_replace('.php', '', $class);

                        $path = str_replace('/', '', 'App\Modules\/' . $path);
                        $path = str_replace('.php', '', $path);

                        $events[$class] = $path;
                    }
                }

                // find all listeners
                $files = Storage::disk('plugins')->files(ucfirst($module['slug']) . '/Listeners');

                if (!empty($files)) {
                    foreach($files as $file) {
                        $path = str_replace('/', '\/', $file);

                        $class = basename($file);
                        $class = str_replace('.php', '', $class);

                        if (!isset($listeners[$class])) {
                            $listeners[$class] = [];
                        }

                        $path = str_replace('/', '', 'App\Modules\/' . $path);
                        $path = str_replace('.php', '', $path);

                        $listeners[$class][] = $path;
                    }
                }
            }

            if (!empty($events) && !empty($listeners)) {
                foreach($events as $class => $path) {
                    $listenerClass = str_replace('Event', 'Listener', $class);

                    if (!empty($listeners[$listenerClass])) {
                        $listen[$path] = $listeners[$listenerClass];
                    }
                }
            }

            return $listen;
        });

        parent::boot();

        //
    }
}
