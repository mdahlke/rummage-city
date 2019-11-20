<template>
    <div class="marker">

        <template v-if="!isSaved">
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
    import {mapboxLatLng, isTrue, createElementFromHtml} from '../../helpers';
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
            }
        },
        props: {
            listing: Object,
            map: Object,
        },
        computed: {
            isSaved: {
                get: function () {
                    return isTrue(this.listing.isSaved);
                },
                set: function (val) {
                    //
                }
            }
        },
        watch: {
            isSaved: function (val, old) {
                this.recreate_marker();
            }
        },
        mounted() {
            if (this.listing) {
                this.add_marker_to_map(this.listing);
            }
        },
        beforeDestroy(){
            this.$emit('removeMarker', this.listing);
        },
        methods: {
            add_marker_to_map(listing) {
                let popup;
                let marker;
                let el;
                let ldate = new moment(listing.active_date[0].start);
                let duration = moment.duration(ldate.diff(moment()));

                this.$emit('removeMarker', this.listing);

                this.title = listing.title;

                // reset the marker
                Object.assign(this.$data, this.$options.data());

                if (duration.as('days') < 1) {
                    this.color = '#42b883';
                    this.strokeColor = '#fff';
                    this.ellipsisColor = '#35495e';
                    this.ellipsisStrokeColor = '#fff';
                }

                if (this.isSaved) {
                    this.color = '#ff444e';
                    this.ellipsisColor = '#ff444e';
                    this.ellipsisStrokeColor = '#ff444e';
                    this.height = 20;
                    this.width = 20;
                }

                if (!listing) {
                    return;
                }
                popup = null;
                marker = null;

                if (listing.id) {
                    popup = this.create_popup(listing);
                    marker = this.create_marker(listing);

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
            create_marker(listing = null, popup = null) {
                const coords = mapboxLatLng(listing);

                if (!coords.lat || !coords.lng) {
                    return false;
                }

                this.marker = new mapboxgl.Marker(this.$vnode.elm, {
                    offset: 0
                }).setLngLat({lng: coords.lng, lat: coords.lat});

                return this.marker;
            },
            add_popup(listing) {
                return new mapboxgl.Popup({className: 'listing__popup'})
                    .setLngLat(mapboxLatLng(listing))
                    .setHTML("<h1>" + listing.title + "</h1>")
                    .setMaxwidth("300px")
                    .addTo(this.map);
            },
            create_popup(listing) {
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
                }
                const popup = new mapboxgl.Popup({
                    className: 'listing__popup',
                    offset: popupOffsets,
                })
                    .setHTML(`<strong>` + listing.title + ` </strong>`)
                    .setMaxWidth("300px");

                popup.on('open', (e, d) => {
                    // this.$emit('set_active_listing', listing);
                    // this.active_listing_id = listing.id;
                });
                popup.on('close', (e, d) => {
                    // we only send the update event if the listing id matches the active_listing_id
                    // this allows other markers to be clicked without this event firing
                    // if (listing.id === this.active_listing_id) {
                    // this.$emit('set_active_listing', {});
                    // }
                });

                return popup;
            },
            recreate_marker() {
                this.marker.remove();
                this.$forceUpdate();
                this.add_marker_to_map(this.listing);
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
