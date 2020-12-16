<?php

namespace Acm\NovaGmap;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaGmap extends Field
{
	public $component = 'nova-gmap';
	public $textAlign = 'center';


	public function __construct($name, $attribute = null, $resolveCallback = null)
	{
		parent::__construct($name, $attribute, $resolveCallback);

		$this->latitude(config('nova-gmaps.default_latitude'))
			 ->longitude(config('nova-gmaps.default_longitude'))
			 ->zoom(config('nova-gmaps.default_zoom'));
	}


	public function latitude($latitude)
	{
		return $this->withMeta(['latitude' => $latitude]);
	}


	public function longitude($longitude)
	{
		return $this->withMeta(['longitude' => $longitude]);
	}

	
	public function zoom($zoom)
	{
		return $this->withMeta(['zoom' => intval($zoom)]);
	}


	protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
	{
		$model->setAttribute($attribute, (object) [
			'latitude'  => Arr::get($request, $attribute.'.latitude'),
			'longitude' => Arr::get($request, $attribute.'.longitude'),
		]);
	}
}
