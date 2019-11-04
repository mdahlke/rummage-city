<template>
    <ul class="listing__dates">
        <li v-for="(date, index) in the_dates"
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
</template>

<script>
    import moment from 'moment';

    import '../../../sass/component/listing-dates.scss';

    export default {
        name: 'ListingDates',
        props: {
            dates: Array,
        },
        created() {
        },
        mounted() {
        },
        computed: {
            the_dates: function () {
                return this.listing_dates(this.dates);
            }
        },
        methods: {
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
            },
        }
    }
</script>
