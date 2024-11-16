<?php

namespace WC_BE\Http\Controllers\DashboardController;

abstract class AbstractOptionsController extends AbstractDashboardController
{
    public function addMenu(): void
    {
        add_options_page(
            $this->pageTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            [$this, 'render']
        );
    }
}