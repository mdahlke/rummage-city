<template>
    <div class="marker">
        <svg width="39" height="40" xmlns="http://www.w3.org/2000/svg">
            <title>{{ title }}</title>

            <g>
                <title>background</title>
                <rect fill="none" id="canvas_background" height="5.43643" width="5.35052" y="-1" x="-1"/>
            </g>
            <g>
                <title>Layer 1</title>
                <path :stroke="strokeColor" stroke-width="1" id="svg_1" stroke-linecap="round"
                      stroke-linejoin="round"
                      d="m19.50242,39.5023c2.03471,-19.49403 9.19609,-16.47947 12.47161,-25.56621a12.61174,11.59174 0 1 0 -25.08335,-1.48118a12.40154,11.39853 0 0 0 0.14014,1.41678c1.8596,8.79571 10.93298,5.5876 12.4716,25.63061z"
                      :fill="color" stroke-miterlimit="10"/>
                <ellipse transform="rotate(0.08227808773517609 19.500000000001684,10.655783653258416) "
                         ry="4.93409"
                         rx="5.20869" id="svg_2" cy="10.65578" cx="19.5" stroke-width="0.5"
                         :fill="ellipsisColor"
                         :stroke="ellipsisStrokeColor"/>
            </g>
        </svg>
    </div>
</template>

<script>
    import {mapbox_latlng, is_true, create_element_from_html} from '../../helpers';
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
                color: '#35495e',
                strokeColor: '#eaeaea',
                ellipsisColor: '#ffffff',
                ellipsisStrokeColor: '#000000',
                marker: {},
            }
        },
        props: {
            listing: Object,
            bus: Object,
            map: Object,
        },
        computed: {
            is_saved: {
                get: function () {
                    return is_true(this.listing.isSaved);
                },
                set: function (val) {
                    //
                }
            }
        },
        watch: {
            is_saved: function (val, old) {
                this.recreate_marker();
            }
        },
        mounted() {
            if (this.listing) {
                this.add_marker_to_map(this.listing);
            }

        },
        methods: {
            add_marker_to_map(listing) {
                let popup;
                let marker;
                let el;
                let ldate = new moment(listing.active_date[0].start);
                let duration = moment.duration(ldate.diff(moment()));

                this.$emit('remove_marker', this.listing);

                this.title = listing.title;

                // reset the marker
                Object.assign(this.$data, this.$options.data());

                if (duration.as('days') < 1) {
                    this.color = '#42b883';
                    this.strokeColor = '#000';
                    this.ellipsisColor = '#35495e';
                    this.ellipsisStrokeColor = '#fff';
                }
                if (this.is_saved) {

                    this.color = '#ff444e';
                    this.ellipsisColor = '#ff444e';
                    this.ellipsisStrokeColor = '#ff444e';
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
                            this.$emit('preload', l);
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
                const coords = mapbox_latlng(listing);

                if (!coords.lat || !coords.lng) {
                    return false;
                }

                this.marker = new mapboxgl.Marker(this.$vnode.elm).setLngLat({lng: coords.lng, lat: coords.lat});

                return this.marker;
            },
            add_popup(listing) {
                return new mapboxgl.Popup({className: 'listing__popup'})
                    .setLngLat(mapbox_latlng(listing))
                    .setHTML("<h1>" + listing.title + "</h1>")
                    .setMaxWidth("300px")
                    .addTo(this.map);
            },
            create_popup(listing) {
                let markerHeight = 20, markerRadius = 10, linearOffset = 25;
                let popupOffsets = {
                    'top': [0, 0],
                    'top-left': [0, 0],
                    'top-right': [0, 0],
                    'bottom': [0, -markerHeight],
                    'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                    'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                    'left': [markerRadius, (markerHeight - markerRadius) * -1],
                    'right': [-markerRadius, (markerHeight - markerRadius) * -1]
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
