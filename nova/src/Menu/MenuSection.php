<?php

namespace Laravel\Nova\Menu;

use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Laravel\Nova\AuthorizedToSee;
use Laravel\Nova\Exceptions\NovaException;
use Laravel\Nova\Fields\Collapsable;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Makeable;
use Laravel\Nova\URL;
use Laravel\Nova\WithBadge;

/**
 * @method static static make(string $name, array|iterable $items = [], string $icon = 'collection')
 */
class MenuSection implements JsonSerializable
{
    use AuthorizedToSee;
    use Collapsable;
    use Macroable;
    use Makeable;
    use WithBadge;

    /**
     * The menu's component.
     *
     * @var string
     */
    public $component = 'menu-section';

    /**
     * The menu's name.
     *
     * @var string
     */
    public $name;

    /**
     * The menu's items.
     *
     * @var \Laravel\Nova\Menu\MenuCollection
     */
    public $items;

    /**
     * the menu's icon.
     *
     * @var string
     */
    public $icon;

    /**
     * The menu's path.
     *
     * @var string|null
     */
    public $path;

    /**
     * Construct a new Menu Section instance.
     *
     * @param  string  $name
     * @param  array|iterable  $items
     * @param  string  $icon
     */
    public function __construct($name, $items = [], $icon = 'collection')
    {
        $this->name = $name;
        $this->items = new MenuCollection($items);
        $this->icon = $icon;
    }

    /**
     * Create a menu from dashboard class.
     *
     * @param  class-string<\Laravel\Nova\Dashboard>  $dashboard
     * @return static
     */
    public static function dashboard($dashboard)
    {
        return with(new $dashboard(), function ($dashboard) {
            return static::make(
                $dashboard->label()
            )->path('/dashboards/'.$dashboard->uriKey())
                ->canSee(function ($request) use ($dashboard) {
                    return $dashboard->authorizedToSee($request);
                });
        });
    }

    /**
     * Create a menu section from a resource class.
     *
     * @param  class-string<\Laravel\Nova\Resource>  $resourceClass
     * @return static
     */
    public static function resource($resourceClass)
    {
        return static::make(
            $resourceClass::label()
        )->path('/resources/'.$resourceClass::uriKey())
            ->canSee(function ($request) use ($resourceClass) {
                return $resourceClass::availableForNavigation($request) && $resourceClass::authorizedToViewAny($request);
            });
    }

    /**
     * Create a menu section from a lens class.
     *
     * @param  class-string<\Laravel\Nova\Resource>  $resourceClass
     * @param  class-string<\Laravel\Nova\Lenses\Lens>  $lensClass
     * @return static
     */
    public static function lens($resourceClass, $lensClass)
    {
        return with(new $lensClass, function ($lens) use ($resourceClass) {
            return static::make($lens->name())
                ->path('/resources/'.$resourceClass::uriKey().'/lens/'.$lens->uriKey())
                ->canSee(function ($request) use ($lens) {
                    return $lens->authorizedToSee($request);
                });
        });
    }

    /**
     * Set path to the menu.
     *
     * @param  string  $path
     * @return $this
     */
    public function path($path)
    {
        $this->path = $path;

        if ($this->collapsable) {
            throw new NovaException('Link menu sections cannot also be collapsable.');
        }

        return $this;
    }

    /**
     * Set the menu section as collapsable.
     *
     * @return $this
     */
    public function collapsable()
    {
        $this->collapsable = true;

        if ($this->path) {
            throw new NovaException('Link menu sections cannot also be collapsable.');
        }

        return $this;
    }

    /**
     * Set icon to the menu.
     *
     * @param  string  $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Prepare the menu for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);
        $url = ! empty($this->path) ? URL::make($this->path) : null;

        return [
            'key' => md5($this->name.'-'.$this->path),
            'name' => $this->name,
            'component' => $this->component,
            'items' => $this->items->authorized($request)->withoutEmptyItems()->all(),
            'collapsable' => $this->collapsable,
            'collapsedByDefault' => $this->collapsedByDefault,
            'icon' => $this->icon,
            'path' => (string) $url,
            'active' => optional($url)->active() ?? false,
            'badge' => $this->resolveBadge(),
        ];
    }
}
