<template>
    <section class="listing__dates">
        <ListingDatesDate
             v-for="(date, index) in datesFormatted"
             :date="date"
             :key="'date-' + date.id"
             class="listing-date"
             :class="(date.end.month === false) ? '' : 'spans-months'"
        />
    </section>
</template>

<script>
    import moment from 'moment';
    import ListingDatesDate from './ListingDatesDate';

    export default {
        name: 'ListingDates',
        components: {ListingDatesDate},
        props: {
            dates: Array,
        },
        date() {
            return {
                dateCount: {
                    type: Number,
                    required: false,
                    default: 0,
                }
            }
        },
        created() {
            this.dateCount = 0;
        },
        computed: {
            datesFormatted: function () {
                let the_dates = [];
                let current;
                let start_date;
                let end_date;
                let start;
                let end;

                this.dates.forEach(date => {
                    current = {};

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

                    current = {
                        id: ++this.dateCount,
                        start,
                        end
                    };
                    current.time = '';

                    the_dates.push(current);
                });

                return the_dates;
            }
        },
        methods: {
            show_year(date) {
                const this_year = new moment().format('YYYY');
                return this_year !== date.start.year && (!date.end.year || (date.end.year && date.start.year === date.end.year));
            },
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/component/listing-dates.scss';
</style>
