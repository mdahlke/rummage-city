<?php

namespace App;


use Jenssegers\Model\Model as OfflineModel;

/*
 +"id": "poi.2199023325185"
      +"type": "Feature"
      +"place_type": array:1 [▶]
      +"relevance": 1
      +"properties": {#459 ▶}
      +"text": "Country Inn & Suites By Radisson"
      +"place_name": "Country Inn & Suites By Radisson, 121 Merwin Way, Fond Du Lac, Wisconsin 54937, United States"
      +"matching_text": "Fond du Lac"
      +"matching_place_name": "Fond du Lac, 121 Merwin Way, Fond Du Lac, Wisconsin 54937, United States"
      +"center": array:2 [▼
        0 => -88.466061
        1 => 43.751304
      ]
      +"geometry": {#460 ▼
        +"coordinates": array:2 [▼
          0 => -88.466061
          1 => 43.751304
        ]
        +"type": "Point"
      }
      +"context": array:4 [▼
        0 => {#461 ▼
          +"id": "postcode.10431599462940500"
          +"text": "54937"
        }
        1 => {#462 ▼
          +"id": "place.8083499776637650"
          +"wikidata": "Q985584"
          +"text": "Fond Du Lac"
        }
        2 => {#463 ▼
          +"id": "region.13898158994669070"
          +"short_code": "US-WI"
          +"wikidata": "Q1537"
          +"text": "Wisconsin"
        }
        3 => {#464 ▼
          +"id": "country.9053006287256050"
          +"short_code": "us"
          +"wikidata": "Q30"
          +"text": "United States"
        }
 */

/**
 * Class MapboxFeature
 * @package App
 *
 * @property float relevance;
 * @property string text;
 * @property string place_name;
 * @property string $matching_text;
 * @property string $matching_place_name;
 * @property array center;
 * @property array $geometry;
 * @property array $bbox;
 * @property array $context;
 */
class MapboxFeature extends OfflineModel {

    protected $fillable = ['id', 'relevance', 'text', 'place_name', 'matching_text', 'matching_place_name', 'center', 'geometry', 'context', 'bbox'];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function getWest() {
        return (float)$this->bbox[0];
    }

    public function getEast() {
        return (float)$this->bbox[2];
    }

    public function getNorth() {
        return (float)$this->bbox[3];
    }

    public function getSouth() {
        return (float)$this->bbox[1];
    }

    public function getLng() {
        return (float)$this->center[0] ?? '';
    }

    public function getLat() {
        return (float)$this->center[1] ?? '';
    }

}
