<?php

namespace WC_BE\Core;

class EventListener
{
    public function __construct() {
        $this->registerEvents();
    }

    public function getEvents(): array
    {
        return [
        ];
    }

    private function registerEvents(): void {
        foreach ($this->getEvents() as $event => $callback) {
            if (is_array($callback) && count($callback) > 2) {
                $function = $callback[0];
                $priority = $callback[1];
                $args = $callback[2];
                if (!has_action($event, $function)) {
                    add_action($event, $function, $priority, $args);
                }
            } else {
                if (!has_action($event, $callback)) {
                    add_action($event, $callback);
                }
            }
        }
    }
}