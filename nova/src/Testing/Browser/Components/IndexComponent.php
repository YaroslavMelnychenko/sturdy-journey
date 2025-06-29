<?php

namespace Laravel\Nova\Testing\Browser\Components;

use Illuminate\Support\Str;
use Laravel\Dusk\Browser;

class IndexComponent extends Component
{
    public $resourceName;

    public $viaRelationship;

    /**
     * Create a new component instance.
     *
     * @param  string  $resourceName
     * @param  string|null  $viaRelationship
     * @return void
     */
    public function __construct($resourceName, $viaRelationship = null)
    {
        $this->resourceName = $resourceName;

        if (! is_null($viaRelationship) && $resourceName !== $viaRelationship) {
            $this->viaRelationship = $viaRelationship;
        }
    }

    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        $selector = '[dusk="'.$this->resourceName.'-index-component"]';

        return sprintf(
            ! is_null($this->viaRelationship) ? '%s[data-relationship="%s"]' : '%s', $selector, $this->viaRelationship
        );
    }

    /**
     * Wait for table to be ready.
     *
     * @param  int|null  $seconds
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function waitForTable(Browser $browser, $seconds = null)
    {
        $browser->waitUntilMissing('@loading-view')
            ->whenAvailable('table[data-testid="resource-table"]', function ($browser) use ($seconds) {
                $browser->waitFor('> tbody', $seconds);
            }, $seconds);
    }

    /**
     * Wait for empty dialog to be ready.
     *
     * @param  int|null  $seconds
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function waitForEmptyDialog(Browser $browser, $seconds = null)
    {
        $browser->waitUntilMissing('@loading-view')
            ->waitFor('div[dusk="'.$this->resourceName.'-empty-dialog"]', $seconds);
    }

    /**
     * Search for the given string.
     *
     * @param  string  $search
     * @return void
     */
    public function searchFor(Browser $browser, $search)
    {
        $browser->type('@search', $search)->pause(1000);
    }

    /**
     * Clear the search field.
     *
     * @return void
     */
    public function clearSearch(Browser $browser)
    {
        $browser->clear('@search')->type('@search', ' ')->pause(1000);
    }

    /**
     * Click the sortable icon for the given attribute.
     *
     * @param  string  $attribute
     * @return void
     */
    public function sortBy(Browser $browser, $attribute)
    {
        $browser->click("@sort-{$attribute}")->waitForTable();
    }

    /**
     * Paginate to the next page of resources.
     *
     * @return void
     */
    public function nextPage(Browser $browser)
    {
        $browser->click('@next')->waitForTable();
    }

    /**
     * Paginate to the previous page of resources.
     *
     * @return void
     */
    public function previousPage(Browser $browser)
    {
        $browser->click('@previous')->waitForTable();
    }

    /**
     * Select all the the resources on current page.
     *
     * @return void
     */
    public function selectAllOnCurrentPage(Browser $browser)
    {
        $browser->within(new SelectAllDropdownComponent(), function ($browser) {
            $browser->selectAllOnCurrentPage();
        });
    }

    /**
     * Un-select all the the resources on current page.
     *
     * @return void
     */
    public function unselectAllOnCurrentPage(Browser $browser)
    {
        $browser->within(new SelectAllDropdownComponent(), function ($browser) {
            $browser->unselectAllOnCurrentPage();
        });
    }

    /**
     * Select all the matching resources.
     *
     * @return void
     */
    public function selectAllMatching(Browser $browser)
    {
        $browser->within(new SelectAllDropdownComponent(), function ($browser) {
            $browser->selectAllMatching();
        });
    }

    /**
     * Un-select all the matching resources.
     *
     * @return void
     */
    public function unselectAllMatching(Browser $browser)
    {
        $browser->within(new SelectAllDropdownComponent(), function ($browser) {
            $browser->unselectAllMatching();
        });
    }

    /**
     * Assert on the matching total matching count text.
     *
     * @param  int  $count
     * @return void
     */
    public function assertSelectAllMatchingCount(Browser $browser, $count)
    {
        $browser->within(new SelectAllDropdownComponent(), function ($browser) use ($count) {
            $browser->assertSelectAllMatchingCount($count);
        });
    }

    /**
     * Set the given filter and filter value for the index.
     *
     * @param  callable|null  $fieldCallback
     * @return void
     */
    public function runFilter(Browser $browser, $fieldCallback = null)
    {
        $browser->openFilterSelector()->pause(500);

        if (! is_null($fieldCallback)) {
            $browser->elsewhere('[data-menu-open="true"]', function ($browser) use ($fieldCallback) {
                $fieldCallback($browser);
            });
        }

        $browser->closeCurrentDropdown()->pause(1000);
    }

    /**
     * Reset current filter value for the index.
     *
     * @return void
     */
    public function resetFilter(Browser $browser)
    {
        $this->runFilter($browser, function ($browser) {
            $browser->press(Str::upper(__('Reset Filters')));
        });
    }

    /**
     * Assert current filter count for the index.
     *
     * @return void
     */
    public function assertFilterCount(Browser $browser, int $count)
    {
        $browser->within('div[dusk="filter-selector"] button div.toolbar-button', function ($browser) use ($count) {
            if ($count <= 0) {
                $browser->assertMissing('span');
            } else {
                $browser->assertVisible('span')
                    ->assertSeeIn('span', $count);
            }
        });
    }

    /**
     * Set the per page value for the index.
     *
     * @return void
     */
    public function setPerPage(Browser $browser, $value)
    {
        $this->runFilter($browser, function ($browser) use ($value) {
            $browser->whenAvailable('select[dusk="per-page-select"]', function ($browser) use ($value) {
                $browser->select('', $value);
            });
        });
    }

    /**
     * Set the given filter and filter value for the index.
     *
     * @param  string  $name
     * @param  string  $value
     * @return void
     */
    public function selectFilter(Browser $browser, $name, $value)
    {
        $this->runFilter($browser, function ($browser) use ($name, $value) {
            $browser->whenAvailable('select[dusk="'.$name.'-select-filter"]', function ($browser) use ($value) {
                $browser->select('', $value);
            });
        });
    }

    /**
     * Indicate that trashed records should not be displayed.
     *
     * @return void
     */
    public function withoutTrashed(Browser $browser)
    {
        $this->runFilter($browser, function ($browser) {
            $browser->whenAvailable('[dusk="filter-soft-deletes"]', function ($browser) {
                $browser->select('select[dusk="trashed-select"]', '');
            });
        });
    }

    /**
     * Indicate that only trashed records should be displayed.
     *
     * @return void
     */
    public function onlyTrashed(Browser $browser)
    {
        $this->runFilter($browser, function ($browser) {
            $browser->whenAvailable('[dusk="filter-soft-deletes"]', function ($browser) {
                $browser->select('select[dusk="trashed-select"]', 'only');
            });
        });
    }

    /**
     * Indicate that trashed records should be displayed.
     *
     * @return void
     */
    public function withTrashed(Browser $browser)
    {
        $this->runFilter($browser, function ($browser) {
            $browser->whenAvailable('[dusk="filter-soft-deletes"]', function ($browser) {
                $browser->select('select[dusk="trashed-select"]', 'with');
            });
        });
    }

    /**
     * Open the action selector.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function openActionSelector(Browser $browser)
    {
        $browser->whenAvailable('@action-select', function ($browser) {
            $browser->click('')->pause(100);
        });
    }

    /**
     * Open the standalone action selector.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function openStandaloneActionSelector(Browser $browser)
    {
        $browser->whenAvailable('@index-standalone-action-dropdown', function ($browser) {
            $browser->click('')->pause(100);
        });
    }

    /**
     * Open the filter selector.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function openFilterSelector(Browser $browser)
    {
        $browser->whenAvailable('@filter-selector', function ($browser) {
            $browser->click('')->pause(100);
        });
    }

    /**
     * Open the action selector.
     *
     * @param  int|string  $id
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function openControlSelectorById(Browser $browser, $id)
    {
        $browser->closeCurrentDropdown()
            ->whenAvailable("@{$id}-control-selector", function ($browser) {
                $browser->click('')->pause(300);
            });
    }

    /**
     * assert the action selector is present by ID.
     *
     * @param  int|string  $id
     * @return void
     */
    public function assertPresentControlSelectorById(Browser $browser, $id)
    {
        $browser->assertPresent("@{$id}-control-selector");
    }

    /**
     * assert the action selector is missing by ID.
     *
     * @param  int|string  $id
     * @return void
     */
    public function assertMissingControlSelectorById(Browser $browser, $id)
    {
        $browser->assertMissing("@{$id}-control-selector");
    }

    /**
     * Select the action with the given URI key.
     *
     * @param  string  $uriKey
     * @param  callable  $fieldCallback
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function selectAction(Browser $browser, $uriKey, $fieldCallback)
    {
        $browser->whenAvailable('select[dusk="action-select"]', function ($browser) use ($uriKey) {
            $browser->select('', $uriKey)
                ->pause(100)
                ->assertSelected('', '');
        });

        $browser->elsewhereWhenAvailable(new Modals\ConfirmActionModalComponent(), function ($browser) use ($fieldCallback) {
            $fieldCallback($browser);
        });
    }

    /**
     * Select the standalone action with the given URI key.
     *
     * @param  string  $uriKey
     * @param  callable  $fieldCallback
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function selectStandaloneAction(Browser $browser, $uriKey, $fieldCallback)
    {
        $browser->whenAvailable('@index-standalone-action-dropdown', function ($browser) {
            $browser->click('');
        })->elseWhereWhenAvailable('div[data-menu-open="true"]', function ($browser) use ($uriKey) {
            $browser->click("button[data-action-id='{$uriKey}']");
        });

        $browser->elsewhereWhenAvailable(new Modals\ConfirmActionModalComponent(), function ($browser) use ($fieldCallback) {
            $fieldCallback($browser);
        });
    }

    /**
     * Run the action with the given URI key.
     *
     * @param  string  $uriKey
     * @param  callable|null  $fieldCallback
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function runAction(Browser $browser, $uriKey, $fieldCallback = null)
    {
        $this->selectAction($browser, $uriKey, function ($browser) use ($fieldCallback) {
            if ($fieldCallback) {
                $fieldCallback($browser);
            }

            $browser->waitForText('Run Action')->click('[dusk="confirm-action-button"]')->pause(250);
        });
    }

    /**
     * Run the standalone action with the given URI key.
     *
     * @param  string  $uriKey
     * @param  callable|null  $fieldCallback
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function runStandaloneAction(Browser $browser, $uriKey, $fieldCallback = null)
    {
        $this->selectStandaloneAction($browser, $uriKey, function ($browser) use ($fieldCallback) {
            if ($fieldCallback) {
                $fieldCallback($browser);
            }

            $browser->waitForText('Run Action')->click('[dusk="confirm-action-button"]')->pause(250);
        });
    }

    /**
     * Select the action with the given URI key.
     *
     * @param  int|string  $id
     * @param  string  $uriKey
     * @param  callable  $fieldCallback
     * @return void
     */
    public function selectInlineAction(Browser $browser, $id, $uriKey, $fieldCallback)
    {
        $browser->openControlSelectorById($id)
            ->elseWhereWhenAvailable('div[data-menu-open="true"]', function ($browser) use ($uriKey) {
                $browser->click("button[data-action-id='{$uriKey}']");
            })->pause(500);

        $browser->elsewhereWhenAvailable(new Modals\ConfirmActionModalComponent(), function ($browser) use ($fieldCallback) {
            $fieldCallback($browser);
        });
    }

    /**
     * Run the action with the given URI key.
     *
     * @param  int|string  $id
     * @param  string  $uriKey
     * @param  callable|null  $fieldCallback
     * @return void
     */
    public function runInlineAction(Browser $browser, $id, $uriKey, $fieldCallback = null)
    {
        $this->selectInlineAction($browser, $id, $uriKey, function ($browser) use ($fieldCallback) {
            if ($fieldCallback) {
                $fieldCallback($browser);
            }

            $browser->click('[dusk="confirm-action-button"]')->pause(250);
        });
    }

    /**
     * Check the user at the given resource table row index.
     *
     * @param  int|string  $id
     * @param  int|string|null  $pivotId
     * @return void
     */
    public function clickCheckboxForId(Browser $browser, $id, $pivotId = null)
    {
        if (! is_null($pivotId)) {
            $browser->click('[data-pivot-id="'.$pivotId.'"][dusk="'.$id.'-row"] input.checkbox');
        } else {
            $browser->click('[dusk="'.$id.'-row"] input.checkbox');
        }

        $browser->pause(175);
    }

    /**
     * Replicate the given resource table row index.
     *
     * @param  int|string  $id
     * @return void
     */
    public function replicateResourceById(Browser $browser, $id)
    {
        $browser->openControlSelectorById($id)
            ->elsewhereWhenAvailable("@{$id}-replicate-button", function ($browser) {
                $browser->click('');
            })->pause(500);
    }

    /**
     * Preview the given resource table row index.
     *
     * @param  int|string  $id
     * @return void
     */
    public function previewResourceById(Browser $browser, $id)
    {
        $browser->openControlSelectorById($id)
            ->elsewhereWhenAvailable("@{$id}-preview-button", function ($browser) {
                $browser->click('');
            })->pause(500);
    }

    /**
     * Delete the user at the given resource table row index.
     *
     * @param  int|string  $id
     * @return void
     */
    public function deleteResourceById(Browser $browser, $id)
    {
        $browser->click("@{$id}-delete-button")
            ->elsewhereWhenAvailable(new Modals\DeleteResourceModalComponent(), function ($browser) {
                $browser->confirm();
            })->pause(500);
    }

    /**
     * Restore the user at the given resource table row index.
     *
     * @param  int|string  $id
     * @return void
     */
    public function restoreResourceById(Browser $browser, $id)
    {
        $browser->click("@{$id}-restore-button")
            ->elsewhereWhenAvailable(new Modals\RestoreResourceModalComponent(), function ($browser) {
                $browser->confirm();
            })->pause(500);
    }

    /**
     * View the user at the given resource table row index.
     *
     * @param  int|string  $id
     * @return void
     */
    public function viewResourceById(Browser $browser, $id)
    {
        $browser->click("@{$id}-view-button")->pause(500);
    }

    /**
     * Edit the user at the given resource table row index.
     *
     * @param  int|string  $id
     * @return void
     */
    public function editResourceById(Browser $browser, $id)
    {
        $browser->click("@{$id}-edit-button")->pause(500);
    }

    /**
     * Delete the resources selected via checkboxes.
     *
     * @return void
     */
    public function deleteSelected(Browser $browser)
    {
        $browser->click('@delete-menu')
            ->pause(300)
            ->elsewhere('', function ($browser) {
                $browser->click('[dusk="delete-selected-button"]')
                    ->elsewhereWhenAvailable(new Modals\DeleteResourceModalComponent(), function ($browser) {
                        $browser->confirm();
                    });
            })->pause(1000);
    }

    /**
     * Restore the resources selected via checkboxes.
     *
     * @return void
     */
    public function restoreSelected(Browser $browser)
    {
        $browser->click('@delete-menu')
            ->pause(300)
            ->elsewhere('', function ($browser) {
                $browser->click('[dusk="restore-selected-button"]')
                    ->elsewhereWhenAvailable(new Modals\RestoreResourceModalComponent(), function ($browser) {
                        $browser->confirm();
                    });
            })->pause(1000);
    }

    /**
     * Restore the resources selected via checkboxes.
     *
     * @return void
     */
    public function forceDeleteSelected(Browser $browser)
    {
        $browser->click('@delete-menu')
            ->pause(300)
            ->elsewhere('', function ($browser) {
                $browser->click('[dusk="force-delete-selected-button"]')
                    ->elsewhereWhenAvailable(new Modals\DeleteResourceModalComponent(), function ($browser) {
                        $browser->confirm();
                    });
            })->pause(1000);
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function assert(Browser $browser)
    {
        $browser->pause(500);

        tap($this->selector(), function ($selector) use ($browser) {
            $browser->waitFor($selector)
                ->assertVisible($selector)
                ->scrollIntoView($selector);
        });
    }

    /**
     * Assert that the given resource is visible.
     *
     * @param  int|string  $id
     * @param  int|string|null  $pivotId
     * @return void
     */
    public function assertSeeResource(Browser $browser, $id, $pivotId = null)
    {
        if (! is_null($pivotId)) {
            $browser->assertVisible('[dusk="'.$id.'-row"][data-pivot-id="'.$pivotId.'"]');
        } else {
            $browser->assertVisible("@{$id}-row");
        }
    }

    /**
     * Assert that the given resource is not visible.
     *
     * @param  int|string  $id
     * @param  int|string|null  $pivotId
     * @return void
     */
    public function assertDontSeeResource(Browser $browser, $id, $pivotId = null)
    {
        if (! is_null($pivotId)) {
            $browser->assertMissing('[dusk="'.$id.'-row"][data-pivot-id="'.$pivotId.'"]');
        } else {
            $browser->assertMissing("@{$id}-row");
        }
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
