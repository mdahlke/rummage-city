<template>
    <div>
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
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        name: 'ListingDatesDate',
        props: {
            date: Object,
        },
        created() {
            this.dateCount = 0;
        },
        computed: {},
        methods: {
            show_year() {
                const this_year = new moment().format('YYYY');
                return this_year !== this.date.start.year && (!this.date.end.year || (this.date.end.year && this.date.start.year === this.date.end.year));
            },
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/component/listing-dates.scss';
</style>
