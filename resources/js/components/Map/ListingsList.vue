<template>
    <section id="listings__list" class="listings-list">
        <template v-if="$parent.visible_listings.length">

            <section v-for="(listing, index) in $parent.visible_listings"
                     :id="'listing-'+ listing.id"
                     class="listing"
                     :class="{ 'active': (listing.id === $parent.active_listing.id) }">
                <!--                     v-on:mouseenter="$emit('set_active_listing', listing)"-->
                <div class="listing-wrap">

                    <div class="listing__main-info"
                         :class="{ 'has-image': listing.image.length}"
                         @click="view(listing.id)">
                        <div class="listing__featured-image" v-if="listing.image.length">
                            <div class="featured-image__blur"
                                 :style="'background-image: url('+listing.image[0].url+')'"></div>
                            <div class="featured-image__image"
                                 :style="'background-image: url('+listing.image[0].url+')'"></div>
                        </div>
                        <div class="listing__content">
                            <h1 class="listing__title">{{ listing.title }}</h1>
                            <h3 class="listing__address">{{ listing.address }}</h3>
                        </div>
                    </div>

                    <div class="listing__content">
                        <router-link :to="{ name: 'listing.view', params: { id: listing.id }}">View</router-link>

                        <ul class="listing__dates">
                            <li v-for="(date, index) in listing_dates(listing.active_date)"
                                :class="(date.end.month === false) ? '' : 'spans-months'">
                                <div class="date-wrap">
                                    <template v-if="date.end.month === false">
                                        <div class="calendar">
                                            <span class="month start">{{ date.start.month }}</span>
                                            <template v-if="date.end.day && date.start.day !== date.end.day">
                                                <div class="day-with-end">
                                                    <span class="day start">{{ date.start.day }}</span>
                                                    <span class="delimiter">&dash;</span>
                                                    <span class="day end">{{ date.end.day}}</span>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <span class="day start">{{ date.start.day }}</span>
                                            </template>
                                            <span class="year start"
                                                  v-if="show_year(date)">{{ date.start.year }}</span>
                                        </div>
                                        <div class="clock">
                                            <span class="time start">{{ date.start.time }}</span>
                                            <span class="delimiter">&dash;</span>
                                            <span class="time start">{{ date.end.time }}</span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="calendar-span">
                                            <div class="calendar start">
                                                <span class="month start">{{ date.start.month }}</span>
                                                <span class="day start">{{ date.start.day }}</span>
                                                <span class="year start"
                                                      v-if="show_year(date)">{{ date.start.year }}</span>
                                            </div>
                                            <div class="calendar end" v-if="date.end.month">
                                                <span class="month end">{{ date.end.month }}</span>
                                                <span class="day end">{{ date.end.day }}</span>
                                                <span class="year end"
                                                      v-if="show_year(date)">{{ date.start.year }}</span>
                                            </div>
                                        </div>
                                        <div class="clock">
                                            <span class="time start">{{ date.start.time }}</span>
                                            <span class="delimiter">&dash;</span>
                                            <span class="time end">{{ date.end.time }}</span>
                                        </div>
                                    </template>
                                </div>
                            </li>
                        </ul>
                        <div class="listing__actions">
                            <ul class="list-inline">
                                <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" v-if="listing.isSaved"
                                              @click="remove_saved(listing)">
                                            <i class="fas fa-heart"></i> Saved</span>
                                    <span class="cursor-pointer" v-else @click="save(listing)">
                                            <i class="far fa-heart"> Save</i></span>
                                </li>
                                <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="$parent.highlight_on_map(listing)">
                                            <i class="fas fa-map"></i> Show on Map</span>
                                </li>
                                <li class="list-inline-item listing__action">
                                        <span class="cursor-pointer" @click="$parent.zoom_to_on_map(listing)">
                                            <i class="fas fa-crosshairs"></i> Zoom to</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </template>
        <template v-else>
            <section>
                There are no listings in this area.
            </section>
        </template>
    </section>
</template>

<script>
    import MapboxPopup from './MapboxPopup.vue';
    import {mapbox_latlng} from '../../helpers';
    import moment from 'moment';

    import '../../../sass/component/listings-list.scss';
    import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';

    export default {
        name: 'ListingsList',
        created() {
            console.log(this.$parent.visible_listings);
        },
        mounted() {
        },
        methods: {
            view(id) {
                this.$router.push({name: 'listing.view', params: {id}})
            },
            save(listing) {
                if (!listing.isSaved) {
                    axios.post(listing.saveUrl).then(function (e) {
                        listing.isSaved = true;
                    });
                }
            },
            remove_saved(listing) {
                if (listing.isSaved) {
                    axios.post(listing.removeSavedUrl).then((e) => {
                        listing.isSaved = false;
                    });
                }
            },
            listing_dates(dates) {
                let date;
                let the_dates = [];
                let current;
                let start_date;
                let end_date;
                let start;
                let end;

                for (let i = 0; i < dates.length; i++) {
                    current = {};
                    date = dates[i];

                    start_date = moment(date.start);
                    end_date = moment(date.end);
                    start = {
                        day: start_date.format('DD'),
                        month: start_date.format('MMM'),
                        year: start_date.format('YYYY'),
                        time: start_date.format('h:mm a'),
                    };

                    end = {
                        day: end_date.format('DD'),
                        month: end_date.format('MMM'),
                        year: end_date.format('YYYY'),
                        time: end_date.format('h:mm a'),
                    };

                    if (start.day === end.day) {
                        end.day = false;
                    }
                    if (start.month === end.month) {
                        end.month = false;
                    }
                    if (start.year === end.year) {
                        end.year = false;
                    }

                    current = {start, end};
                    current.time = '';

                    the_dates.push(current);
                }

                return the_dates;

            },
            show_year(date) {
                const this_year = new moment().format('YYYY');
                return this_year !== date.start.year && (!date.end.year || (date.end.year && date.start.year === date.end.year));
            }
        }
    };

</script>
