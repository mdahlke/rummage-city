<template>
    <div class="listings">
        <aside id="listings__sidebar">
            <listings-list @update_url></listings-list>
        </aside>

        <listings-map @update_visible="update_visible" @update_url="update_url"></listings-map>
    </div>
</template>

<script>
    import ListingsMap from '../components/Map/ListingsMap.vue';
    import ListingsList from '../components/Map/ListingsList.vue';
    import {setPage, updateQueryStringParameter} from '../helpers';
    import '../../sass/component/listings-map.scss';

    export default {
        name: 'Listings',
        components: {
            ListingsList,
            ListingsMap
        },
        data() {
            return {
                map: {},
                platform: {},
                mapEvents: {},
                data_points: [],
                icon: null,
                listing_ids: [],
                visible_listings: [],
                loaded: false,
                lat: 43.75171,
                lng: -88.44867,
                zoom: 10,
                searchState: {},
            };
        },
        props: {
            listings: Array,
            search: Object,
            appId: {
                type: String,
                default: 'lQPofquBtIF1aAOEarx7'
            },
            appCode: {
                type: String,
                default: '28icBNVvG484yi09NA2c1g'
            },
            mapLat: {
                type: String,
                default: '43.75171'
            },
            mapLng: {
                type: String,
                default: '-88.44867'
            },
            mapZoom: {
                type: Number,
                default: 10
            },
            width: {
                type: String,
                default: '100%'
            },
            height: {
                type: String,
                default: 'calc(100vh - 50px)'
            },
            svg: {
                type: String,
                default: null
            }
        },
        created() {
            this.platform = new H.service.Platform({
                'app_id': this.appId,
                'apikey': '7W5ZSgnP_hvci-01R4EbN1_T17e_5x1zVr54MheJxTk'
            });
            if (this.svg) {
                this.icon = new H.map.Icon(this.svg);
            }

            if (this.listings) {
                this.visible_listings = this.listings;
            }

            if (this.search) {
                if (this.search.bounds.lat && this.search.bounds.lng) {
                    this.lat = this.search.bounds.lat;
                    this.lng = this.search.bounds.lng;
                }

                if (this.search.zoom) {
                    this.zoom = this.search.zoom;
                }

                if (this.search.pitch) {
                    this.pitch = this.search.pitch;
                }

                if (this.search.bearing) {
                    this.bearing = this.search.bearing;
                }
            }

        },
        mounted() {
        },
        methods: {
            update_visible(listings) {
                this.visible_listings = listings;
            },
            update_url(update) {
                update = _.extend({
                    bounds: null,
                    listing: null,
                    zoom: null,
                    pitch: null,
                    bearing: null
                }, update);

                console.log({update});

                let url = document.URL;
                url = updateQueryStringParameter(url, 'searchState', JSON.stringify(update));

                setPage(url, 'Title');

            }
        }
    };

</script>
