<?php

namespace App\Traits;

trait GeoCode
{
    /**
     * Hàm tìm kiếm, tỉnh, thành phố, quận, huyện dựa vào trường text
     */
    public function getAddressFromText($address, $provinces, $districts)
    {
        $province = null;
        $district = null;

        foreach ($provinces as $item) {
            if (strpos($address, $item->name) !== false) {
                $province = $item;
                break;
            }
        }

        foreach ($districts as $item) {
            if (strpos($address, $item->name) !== false) {
                $district = $item;
                if (empty($province)) {
                    $province = $provinces->firstWhere('id', $district->province_id);
                }
                break;
            }
        }

        return [
            'province' => $province,
            'district' => $district,
        ];
    }

    public function getLatLonByAddressText($address, $curl = false)
    {
        $result = [];
        $lat = null;
        $long = null;
        $requestFields = [
            'address' => urlencode($address),
            'key' => config('app.google_api_key')
        ];
        $requestFieldsString = '';
        foreach ($requestFields as $key => $value) {
            $requestFieldsString .= $key . '=' . $value . '&';
        }
        rtrim($requestFieldsString, '&');

        $data = json_decode(self::getGoogleGeocodingApi($requestFieldsString, $curl), true);
        if (!empty($data['results'])) {
            foreach ($data['results'] as $item) {
                if (isset($item['geometry'])) {
                    if (isset($item['geometry']['location'])) {
                        $lat = $item['geometry']['location']['lat'];
                        $long = $item['geometry']['location']['lng'];
                        break;
                    }
                }
            }
        }

        return [
            'lat' => $lat,
            'long' => $long
        ];
    }

    public function getFullAddress($address, $provinces, $districts)
    {
        $latLon = $this->getLatLonByAddressText($address);
        $districtProvince = $this->getAddressFromText($address, $provinces, $districts);
        return [
            'lat' => $latLon['lat'],
            'long' => $latLon['long'],
            'province' => $districtProvince['province'],
            'district' => $districtProvince['district']
        ];
    }

    private function getGoogleGeocodingApi($requestFieldsString, $curl = false)
    {
        try {
            $googleGeocodeApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json?';
            if ($curl) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL, $googleGeocodeApiUrl . $requestFieldsString);
                $result = curl_exec($ch);
                curl_close($ch);
            } else
                $result = file_get_contents($googleGeocodeApiUrl . $requestFieldsString);
            return $result;
        } catch (\Exception $e) {
            return '';
        }
    }

    function getAddressByLatLon($lat, $lon, $provinces, $districts)
    {
        $result = [];
        $data = [];
        $latLon = doubleval($lat) . ',' . doubleval($lon);

        $requestFields = [
            'latlng' => urlencode($latLon),
            'key' => config('app.google_api_key')
        ];
        $requestFieldsString = '';
        foreach ($requestFields as $key => $value) {
            $requestFieldsString .= $key . '=' . $value . '&';
        }
        rtrim($requestFieldsString, '&');

        try {
            $data = json_decode(self::getGoogleGeocodingApi($requestFieldsString, false), true);
        } catch (\Exception $exception) {
            # log error
        }

        if (!empty($data['results']) && isset($data['results'][0])) {
            $transformedResult = self::transform($data['results'][0]);
            if (!empty($transformedResult)) {
                $province = $provinces->firstWhere('name', $transformedResult['state_province']);
                if (!empty($province)) {
                    $transformedResult['province_id'] = $province->id;
                } else {
                    $transformedResult['province_id'] = null;
                }

                $district = $districts->firstWhere('name', $transformedResult['district']);
                if (!empty($district)) {
                    $transformedResult['district_id'] = $district->id;
                } else {
                    $transformedResult['district_id'] = null;
                }
                $result[] = $transformedResult;
            }
        }

        return $result;
    }

    public static function transform($geocodeResult)
    {
        $transformedResult = [];
        $transformedResult['district'] = "";
        $transformedResult['state_province'] = "";
        $transformedResult['country'] = "";
        $transformedResult['street'] = "";

        if (isset($geocodeResult['address_components'])) {
            $addressComponents = $geocodeResult['address_components'];
            $transformedResult['street'] = '';
            $streetSuffix = [];

            foreach ($addressComponents as $addressComponent) {
                if (in_array("political", $addressComponent['types'])) {
                    if (in_array("country", $addressComponent['types'])) {
                        $transformedResult['country'] = $addressComponent['long_name'];
                    } elseif (in_array("administrative_area_level_1", $addressComponent['types'])) {
                        $transformedResult['state_province'] = $addressComponent['long_name'];
                    } elseif (in_array("administrative_area_level_2", $addressComponent['types'])) {
                        $transformedResult['district'] = $addressComponent['long_name'];
                    } elseif (in_array("sublocality", $addressComponent['types'])) {
                        $streetSuffix[] = $addressComponent['long_name'];
                    }
                } else {
                    $transformedResult['street'] .= $addressComponent['long_name'] . ', ';
                }
            }
            if (count($streetSuffix) > 0)
                $transformedResult['street'] .= join(', ', $streetSuffix);
            if (substr($transformedResult['street'], strlen($transformedResult['street']) - 3) == ', ') {
                $transformedResult['street'] = substr($transformedResult['street'], 0, strlen($transformedResult['street']) - 3);
            }
        }

        if (isset($geocodeResult['name']) && isset($geocodeResult['vicinity'])) {
            $transformedResult['street'] = $geocodeResult['vicinity'];
        }

        return $transformedResult;
    }
}
