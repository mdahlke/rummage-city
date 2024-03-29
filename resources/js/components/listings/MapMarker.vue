<template>
    <div :id="'marker-' + listing.id"
         class="marker"
    >

        <template v-if="!saved">
            <svg :width="width" :height="height" xmlns="http://www.w3.org/2000/svg">
                <title>{{ title }}</title>
                <g>
                    <title>{{ title }}</title>
                    <ellipse
                            :stroke="strokeColor"
                            :ry="((height-3)/2)"
                            :rx="((width-3)/2)"
                            :cy="(height/2)"
                            :cx="(width/2)"
                            fill-opacity="null"
                            stroke-opacity="null"
                            stroke-width="2"
                            :fill="color"
                    />
                </g>
            </svg>
        </template>
        <template v-else>
            <svg :width="width" :height="height" xmlns="http://www.w3.org/2000/svg">
                <title>{{ title }}</title>
                <g>
                    <title>{{ title }}</title>
                    <path :stroke="strokeColor"
                          d="m0.8639,7.68563l7.01874,0l2.16884,-6.94115l2.16885,6.94115l7.01874,0l-5.67827,4.28982l2.16896,6.94115l-5.67827,-4.28993l-5.67827,4.28993l2.16896,-6.94115l-5.67827,-4.28982z"
                          stroke-opacity="null"
                          stroke-width=".5"
                          :cy="(height/2)"
                          :cx="(width/2)"
                          fill="#ffffaa"/>
                    <path :stroke="color"
                          d="m3.2069,8.44376l5.23011,0l1.61614,-5.17185l1.61615,5.17185l5.23011,0l-4.23124,3.19634l1.61623,5.17185l-4.23124,-3.19643l-4.23124,3.19643l1.61623,-5.17185l-4.23124,-3.19634z"
                          stroke-opacity="null"
                          stroke-width="1"
                          :cy="(height/2)"
                          :cx="(width/2)"
                          :fill="color"/>
                </g>
            </svg>
        </template>
    </div>
</template>

<script>
    import {mapboxLatLng, isTrue, createElementFromHtml, isListingToday} from '../../helpers';
    import Popup from './Popup.vue';
    import moment from 'moment';
    import mapboxgl from 'mapbox-gl';
    import {listing_mixin} from './shared';

    export default {
        name: 'MapMarker',
        components: {Popup},
        mixins: [listing_mixin],
        data() {
            return {
                title: 'Map Marker',
                color: '#ff7e67',
                strokeColor: '#fff',
                ellipsisColor: '#ffffff',
                ellipsisStrokeColor: '#000000',
                marker: {},
                height: 15,
                width: 15,
            };
        },
        props: {
            listing: Object,
            map: Object,
        },
        computed: {
            saved() {
                return isTrue(this.listing.isSaved);
            }
        },
        mounted() {
            if (this.listing) {
                this.addMarkerToMap(this.listing);
            }
        },
        beforeDestroy() {
            this.$emit('removeMarker', this.listing);
        },
        methods: {
            addMarkerToMap(listing) {

                if (!listing) {
                    return;
                }

                let popup;
                let marker;
                const isToday = isListingToday(...listing.active_date);

                this.$emit('removeMarker', this.listing);

                this.title = listing.title;

                // reset the marker
                Object.assign(this.$data, this.$options.data());

                if (isToday) {
                    this.color = '#42b883';
                    this.strokeColor = '#fff';
                    this.ellipsisColor = '#35495e';
                    this.ellipsisStrokeColor = '#fff';
                }

                if (this.saved) {
                    this.height = 20;
                    this.width = 20;

                    if (!isToday) {
                        this.color = '#ff444e';
                        this.ellipsisColor = '#ff444e';
                        this.ellipsisStrokeColor = '#ff444e';
                    }
                }

                popup = null;
                marker = null;

                if (listing.id) {
                    popup = this.createPopup(listing);
                    marker = this.createMarker(listing);

                    ((l, m) => {
                        m.getElement().addEventListener('click', () => {
                            this.view_listing(l);
                        });

                        m.getElement().addEventListener('mouseenter', () => {
                            this.$emit('close_all_popups');
                            m.togglePopup();
                        });

                        m.getElement().addEventListener('mouseleave', () => {
                            this.$emit('close_all_popups');
                        });
                    })(listing, marker);

                    this.add(marker, popup, listing);
                }
            },
            add(marker, popup, listing) {
                if (popup) {
                    marker.setPopup(popup);
                }

                // send to the parent to handle the additions
                this.$emit('add_marker', listing, marker);
            },
            createMarker(listing = null, popup = null) {
                const coords = mapboxLatLng(listing);

                if (!coords.lat || !coords.lng) {
                    return false;
                }

                this.marker = new mapboxgl.Marker(this.$vnode.elm, {
                    offset: 0
                }).setLngLat({lng: coords.lng, lat: coords.lat});

                return this.marker;
            },
            addPopup(listing) {
                return new mapboxgl.Popup({className: 'listing__popup'})
                    .setLngLat(mapboxLatLng(listing))
                    .setHTML('<h1>' + listing.title + '</h1>')
                    .setMaxwidth('300px')
                    .addTo(this.map);
            },
            createPopup(listing) {
                let height = 12, markerRadius = 10, linearOffset = 25;
                let popupOffsets = {
                    'top': [0, 0],
                    'top-left': [0, 0],
                    'top-right': [0, 0],
                    'bottom': [0, -height],
                    'bottom-left': [linearOffset, (height - markerRadius + linearOffset) * -1],
                    'bottom-right': [-linearOffset, (height - markerRadius + linearOffset) * -1],
                    'left': [markerRadius, (height - markerRadius) * -1],
                    'right': [-markerRadius, (height - markerRadius) * -1]
                };
                const popup = new mapboxgl.Popup({
                    className: 'listing__popup',
                    offset: popupOffsets,
                })
                    .setHTML(`<strong>` + listing.title + ` </strong>`)
                    .setMaxWidth('300px');

                return popup;
            },
            recreateMarker() {
                console.log('recreateMarker');
                this.$emit('removeMarker', this.listing);
                // this.$forceUpdate();
                this.addMarkerToMap(this.listing);
            }
        }
    };

</script>

<style lang="scss">
    .marker {

        &:hover {
            cursor: pointer;
        }
    }

    #mapbox .mapboxgl-popup-content {
        box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.55);
    }
</style>
