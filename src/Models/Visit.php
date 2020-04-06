<?php

namespace Hayrullah\ErrorManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
    /**
     * @return MorphTo
     */
    public function visitable()
    {
        return $this->morphTo();
    }

    public static function createVisit($request)
    {
        $visit = new self();
        $visit->previous = $request->previous;

        $geo = GeoIP($request);
        $visit->ip = $geo->ip;
        $visit->locale = $geo->locale;
        $visit->domain = $geo->domain;
        $visit->location = $geo['location'];
        $visit->iso_code = $geo->iso_code;
        $visit->country = $geo->country;
        $visit->city = $geo->city;
        $visit->state = $geo->state;
        $visit->state_name = $geo->state_name;
        $visit->postal_code = $geo->postal_code;
        $visit->lat = $geo->lat;
        $visit->lon = $geo->lon;
        $visit->timezone = $geo->timezone;
        $visit->continent = $geo->continent;
        $visit->currency = $geo->currency;
        $details = @json_decode(@file_get_contents('http://ipinfo.io/'.$geo->ip));
        // Get Full Src
//                $src = $this->getFullSrc($request['full_src']);
//                $visit->src = $src;
        parse_str(parse_url($request['full_src'], PHP_URL_QUERY), $params);

        $visit->org = @$details->org;
        $visit->device = getItemIfExists($params, 'device');
        $visit->device_model = getItemIfExists($params, 'devicemodel');
        $visit->utm_campaign = getItemIfExists($params, 'utm_campaign');
        $visit->utm_content = getItemIfExists($params, 'utm_content');
        $visit->utm_medium = getItemIfExists($params, 'utm_medium');
        $visit->utm_source = getItemIfExists($params, 'utm_source');
        $visit->keyword = getItemIfExists($params, 'keyword');
        $visit->placement = getItemIfExists($params, 'placement');
        $visit->ad_position = getItemIfExists($params, 'adposition');
        $visit->match_type = getItemIfExists($params, 'matchtype');

        return $visit;
    }
}
