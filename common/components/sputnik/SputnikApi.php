<?php

namespace common\components\sputnik;

use yii\helpers\ArrayHelper;

/**
 * Class SputnikApi
 */
class SputnikApi extends \yii\base\Component
{
    /**
     * @var string api key
     */
    public $apiKey;

    public $apiUrl = 'http://search.maps.sputnik.ru/search';

    public function getGeoCodeObject($address)
    {
        if ($address !== null) {
            $querystring = '?q=' . urlencode($address);

            // concat query string
            $querystring = str_replace(' ', '%20', $querystring);

            // query by address string
            $geoCodeUrl = $this->apiUrl
                . $querystring
                . '&apikey=' . $this->apiKey;

            // get geocode object
            try {
                $ch = curl_init($geoCodeUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($ch);
                curl_close($ch);
            } catch (\Exception $e) {
                return null;
            }

            // json decode response
            $response_a = json_decode($response);

            if (isset($response_a->result[0])) {
                return $response_a->result[0];
            }
        }

        return null;
    }

    public function getLocation($address)
    {
        if ($address !== null) {
            if (($geoObject = $this->getGeoCodeObject($address)) !== null) {
                if (isset($geoObject->position)) {
                    return ArrayHelper::toArray($geoObject->position);
                }
            }
        }

        return null;
    }
}