<?php

namespace WC_BE\Http\Controllers\Cron;

abstract class AbstractCronController
{
    protected string $hookName;
    public function __construct() {
        $this->hookName = $this->getHookName();
        add_filter('cron_schedules', [$this, 'customIntervals']);
        $this->registerCron();
    }


    abstract protected function fetch() : void;
    abstract public function customIntervals($schedules): array;
    abstract protected function recurrence() : array;

    private function getHookName() : string
    {
        $className = static::class;
        $baseName = basename(str_replace('\\', '/', $className));
        $baseName = str_replace('CronController', '', $baseName);
        return "fetch_" . strtolower(preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $baseName));
    }
    public function schedule() : void {
        $recurrence = $this->recurrence();

        $interval = $recurrence['interval'] ?? 'daily';
        $time = $recurrence['time'] ?? current_time('timestamp');

        if (!wp_next_scheduled($this->hookName)) {
            wp_schedule_event($time, $interval, $this->hookName);
        }

    }
    private function registerHook() : void {
        add_action($this->hookName, array($this, 'fetch'));
    }
    private function registerCron() : void {
        $this->registerHook();
        add_action('init', array($this, 'schedule'));
    }
}
