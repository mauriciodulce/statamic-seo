<?php

namespace Statamic\Addons\Seo;

use Statamic\API\Content;
use Statamic\API\File;
use Statamic\API\Parse;
use Statamic\API\Str;
use Statamic\API\YAML;
use Statamic\Data\Services\ContentService;
use Statamic\Extend\Tags;

class SeoTags extends Tags
{
    public $content;

    public function __construct(array $properties)
    {
        parent::__construct($properties);

        $contentService = app(ContentService::class);
        $key = site_locale() . '::' . Str::ensureLeft(request()->path(), '/');
        $pageID = $contentService->uris()->get($key);
        $this->content = Content::find($pageID) ? Content::find($pageID)->dataForLocale(app()->getLocale()) : [];
    }

    /**
     * The {{ addon_name }} tag.
     *
     * @return string|array
     */
    public function index()
    {
        $googleTagManager = $this->getGoogleTagManagerHtml();
        $analytics = $this->getGoogleAnalyticsHtml();
        $seoMeta = $this->getSeoMetaHtml();

        return $seoMeta."\n\n".$googleTagManager."\n\n".$analytics;
    }

    protected function getGoogleTagManagerHtml()
    {
        $data = YAML::parse(File::get(settings_path('addons/seo_settings_site_identity.yaml')));
        $view = File::get($this->view('googleTagManager')->getPath());

        $data['content'] = $this->content;
        $data['locale'] = app()->getLocale();

        return Parse::template($view, $data);
    }

    protected function getGoogleAnalyticsHtml()
    {
        $data = YAML::parse(File::get(settings_path('addons/seo_settings_site_identity.yaml')));
        $view = File::get($this->view('googleAnalytics')->getPath());
        $data['content'] = $this->content;
        $data['locale'] = app()->getLocale();

        foreach ($data['google_analytics_plugins'] as $plugin) {
            $data[$plugin] = true;
        }

        return Parse::template($view, $data);
    }

    protected function getSeoMetaHtml()
    {
        $site_creator = YAML::parse(File::get(settings_path('addons/seo_settings_site_creator.yaml')));
        $site_meta = YAML::parse(File::get(settings_path('addons/seo_settings_site_meta.yaml')));
        $social_media = YAML::parse(File::get(settings_path('addons/seo_settings_social_media.yaml')));
        $site_identity = YAML::parse(File::get(settings_path('addons/seo_settings_site_identity.yaml')));

        $data = $site_creator + $site_meta + $social_media + $site_identity;

        $view = File::get($this->view('seo_meta')->getPath());

        $data['content'] = $this->content;
        $data['locale'] = app()->getLocale();

        return Parse::template($view, $data);
    }
}
