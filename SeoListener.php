<?php

namespace Statamic\Addons\Seo;

use Statamic\API\Nav;
use Statamic\Extend\Listener;

class SeoListener extends Listener
{
    /**
     * The events to be listened for, and the methods to call.
     *
     * @var array
     */
    public $events = [
        'cp.nav.created' => 'addNavItems',
    ];

    public function addNavItems($nav)
    {
        $seo = Nav::item('SEO')->route('seo.index')->icon('bar-graph');

        // Add second level navigation items to it
        $seo->add(function (\Statamic\CP\Navigation\Nav $item) {
            $item->add(Nav::item('Site Meta')->route('seo.show', 'site_meta'));
            $item->add(Nav::item('Site Identity')->route('seo.show', 'site_identity'));
            $item->add(Nav::item('Social Media')->route('seo.show', 'social_media'));
            $item->add(Nav::item('Site Creator')->route('seo.show', 'site_creator'));
        });

        $nav->addTo('tools', $seo);
    }
}
