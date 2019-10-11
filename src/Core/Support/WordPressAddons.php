<?php

namespace Themosis\Core\Support;

use Themosis\Core\HooksRepository;
use Themosis\Core\PluginManager;

trait WordPressAddons
{

    /**
     * Register Addons services providers.
     *
     * @param array $providers
     *
     * @return $this
     */
    public function providers(array $providers = [])
    {
        foreach ($providers as $provider) {
            $this->app->register(new $provider($this->app));
        }
        HooksRepository
        return $this;
    }

    public function hooks(array $hooks = [])
    {
        (new HooksRepository($this->app))->load($hooks);
    }
}
