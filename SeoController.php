<?php

namespace Statamic\Addons\Seo;

use Statamic\API\Cache;
use Statamic\API\Fieldset;
use Statamic\API\File;
use Statamic\API\Stache;
use Statamic\API\YAML;
use Statamic\Extend\Controller;
use Statamic\Extend\Fieldtype;

class SeoController extends Controller
{
    /**
     * Maps to your route definition in routes.yaml.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('cp:access');

        return redirect()->route('seo.show', 'site_meta');
    }

    public function show($page)
    {
        $this->authorize('cp:access');

        $data = YAML::parse(File::get(settings_path('addons/seo_settings_'.$page.'.yaml')));

        // Get fieldset, for example: site/addons/Seo/settings_general.yaml
        $fieldset = Fieldset::get('seo.settings_'.$page, 'addon');

        $data = $this->preProcessData($data, $fieldset);
        $data = $this->populateWithBlanks($fieldset, $data);

        return $this->view('settings', [
            'title'        => title_case(str_replace('_', ' ', $page)),
            'slug'         => 'seo-'.$page,
            'content_data' => $data,
            'extra'        => [
                'addon' => 'seo',
            ],
            'content_type' => 'addon',
            'fieldset'     => 'addon.seo.settings_'.$page,
        ]);
    }

    public function saveSettings()
    {
        $this->authorize('cp:access');

        $fieldset = $this->request->get('fieldset');
        $settings = str_replace('addon.', '', $fieldset);
        $filename = str_replace('seo.', '', $settings);

        $data = $this->processFields($settings);

        $contents = YAML::dump($data);

        $file = settings_path('addons/seo_'.$filename.'.yaml');

        File::put($file, $contents);

        Cache::clear();
        Stache::clear();

        $this->success('Settings updated');

        return ['success' => true, 'redirect' => route('seo.show', str_replace('settings_', '', $filename))];
    }

    private function preProcessData($data, $fieldset)
    {
        $fieldtypes = collect($fieldset->fieldtypes())->keyBy(function (Fieldtype $fieldtype) {
            return $fieldtype->getFieldConfig('name');
        });

        foreach ($data as $field_name => $field_data) {
            if ($fieldtype = $fieldtypes->get($field_name)) {
                $data[$field_name] = $fieldtype->preProcess($field_data);
            }
        }

        return $data;
    }

    /**
     * Create the data array, populating it with blank values for all fields in
     * the fieldset, then overriding with the actual data where applicable.
     *
     * @param Fieldset $fieldset
     * @param array    $data
     *
     * @return array
     */
    private function populateWithBlanks($fieldset, $data)
    {
        // Get the fieldtypes
        $fieldtypes = collect($fieldset->fieldtypes())->keyBy(function (Fieldtype $ft) {
            return $ft->getName();
        });

        // Build up the blanks
        $blanks = [];
        foreach ($fieldset->fields() as $name => $config) {
            $blanks[$name] = $fieldtypes->get($name)->blank();

            if (is_null($blanks[$name])) {
                $blanks[$name] = $this->getConfig($name);
            }
        }

        return array_merge($blanks, $data);
    }

    private function processFields($fieldset_name)
    {
        $fieldset = Fieldset::get($fieldset_name, 'addon');
        $data = $this->request->input('fields');

        foreach ($fieldset->fieldtypes() as $field) {
            if (!in_array($field->getName(), array_keys($data))) {
                continue;
            }

            $data[$field->getName()] = $field->process($data[$field->getName()]);
        }

        // Get rid of null fields
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        return $data;
    }
}
