<?php

namespace Statamic\Addons\Seo;

use Statamic\Extend\Modifier;

class SeoModifier extends Modifier
{
    /**
     * Modify a value
     *
     * @param mixed  $value    The value to be modified
     * @param array  $params   Any parameters used in the modifier
     * @param array  $context  Contextual values
     * @return mixed
     */
    public function index($value, $params, $context)
    {
        if (count($params) > 0)
        {
          switch ($params[0])
          {
            case 'is_array':
              // This is just until we get this modifier in core.
              // Issue: https://github.com/statamic/v2-hub/issues/1396
              return is_array($value);
          }
        }
        
        return $value;
    }
}
