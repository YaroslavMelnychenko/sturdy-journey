<?php

namespace Laravel\Nova\Testing\Browser\Components;

use Laravel\Dusk\Browser;

class DetailComponent extends Component
{
    public $resourceName;

    public $resourceId;

    /**
     * Create a new component instance.
     *
     * @param  string  $resourceName
     * @param  int  $resourceId
     * @return void
     */
    public function __construct($resourceName, $resourceId)
    {
        $this->resourceName = $resourceName;
        $this->resourceId = $resourceId;
    }

    /**
     * Open the delete selector.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function openControlSelector(Browser $browser)
    {
        $browser->whenAvailable("@{$this->resourceId}-control-selector", function ($browser) {
            $browser->click('');
        })->pause(100);
    }

    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '@'.$this->resourceName.'-detail-component';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->pause(500);

        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }
}
