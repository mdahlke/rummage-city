<?php

namespace App;

use Jenssegers\Model\Model as OfflineModel;

class Geocode extends OfflineModel {

    //
    public $query;
    public $direction;
    /** @var MapboxFeature[] */
    public $features = [];

    /**
     * Returns the first feature in the array of `features[]` or an empty Feature
     */
    public function feature(): MapboxFeature {
        return $this->features[0] ?? new MapboxFeature();
    }

    public function getBoundingBox() {
        return $this->feature()->geometry;
    }

    public function getCenter() {
        return $this->feature()->center;
    }

    /**
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param mixed $query
     * @return Geocode
     */
    public function setQuery($query) {
        $this->query = $query;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirection() {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     * @return Geocode
     */
    public function setDirection($direction) {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return MapboxFeature[]
     */
    public function getFeatures(): array {
        return $this->features ?? [];
    }

    /**
     * @param MapboxFeature[] $features
     * @return Geocode
     */
    public function setFeatures(array $features): Geocode {
        $this->features = $features;
        return $this;
    }

    /**
     * @param MapboxFeature $feature
     * @return Geocode
     */
    public function addFeature(MapboxFeature $feature): Geocode {
        $features = $this->getFeatures();
        $features[] = $feature;
        $this->setFeatures($features);

        return $this;
    }

}
