<?php

namespace Laravel\Nova\Testing\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Nova\Testing\Browser\Components\FormComponent;
use Laravel\Nova\Testing\Browser\Components\IndexComponent;

class Index extends Page
{
    public $resourceName;

    /**
     * Create a new page instance.
     *
     * @param  string  $resourceName
     * @param  array  $queryParams
     * @return void
     */
    public function __construct($resourceName, $queryParams = [])
    {
        $this->resourceName = $resourceName;
        $this->queryParams = $queryParams;

        $this->setNovaPage("/resources/{$this->resourceName}");
    }

    /**
     * Create the related resource.
     *
     * @param  \Closure|null  $fieldCallback
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function runCreate(Browser $browser, $fieldCallback = null)
    {
        $browser->within(new IndexComponent($this->resourceName), function ($browser) {
            $browser->waitFor('@create-button')->click('@create-button');
        })->on(new Create($this->resourceName));

        if (! is_null($fieldCallback)) {
            $browser->within(new FormComponent(), function ($browser) use ($fieldCallback) {
                call_user_func($fieldCallback, $browser);
            });
        }
    }

    /**
     * Assert that the browser is on the page.
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertOk()->waitFor('@nova-resource-index');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@nova-resource-index' => '#app [data-testid="content"] [dusk="'.$this->resourceName.'-index-component"]',
        ];
    }
}
