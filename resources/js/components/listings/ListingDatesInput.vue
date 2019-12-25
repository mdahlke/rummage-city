<template>
    <section id="listing-dates__form">
        <div class="form-group d-flex">
            <div v-for="(day, index) in days" class="listing-dates__date-wrap">
                <h3>Date {{(index+1)}}</h3>
                <DateInput :key="day.id"
                           :day="day"
                           :start_date="day.start"
                           :end_date="day.end"
                           v-model="days"
                           :class="'date-input'"
                           v-on:remove="remove_date(day)"
                           v-on:update="(...args) => update_day(day, ...args)"></DateInput>
            </div>
            <div class="listing-dates__add-date">
                <i class="fad fa-calendar"></i>
                <br/>
                <button class="btn btn-link" type="button" @click="add_date()"><i class="far fa-plus"></i> Add date
                </button>
            </div>
        </div>
    </section>
</template>

<script>
    import moment from 'moment';

    const DateInput = () => import('./DateInput.vue'/* webpackChunkName: 'js/chunks/date-input' */);

    export default {
        components: {
            DateInput
        },
        data() {
            return {
                min_date_time: '',
                days: [],
                count: 1
            };
        },
        props: {
            dates: {
                type: Array,
                default: () => []
            }
        },
        watch: {
            days: {
                deep: true,
                handler() {
                    this.$emit('update', this.days);
                }
            }
        },
        mounted() {
            console.log(this.$props, this.$data);
            this.min_date_time = moment(new Date()).format();

            console.log('dates: ', this.dates);

            for (let i in this.dates) {
                if (this.dates.hasOwnProperty(i)) {
                    let d = this.dates[i];
                    console.log({d});
                    d.id = this.count++;

                    this.days.push(d);
                }
            }
        },
        methods: {
            add_date() {
                const last = _.last(this.days) || {};

                if (!last) {
                    last.start = moment().format();
                    last.end = moment().add('8', 'hours').format();
                }

                const start = moment(last.start);
                const end = moment(last.end);

                let data = {
                    id: this.count++,
                    start: start.add(1, 'day').format(),
                    end: end.add(1, 'day').format()
                };

                this.days.push(data);
            },
            remove_date(date) {
                this.days.splice(this.days.indexOf(date), 1);
            },
            /**
             * Data must contain the slot (start/end) and the date/time value
             *
             * @param day
             * @param data
             */
            update_day(day, data) {
                this.days[this.days.indexOf(day)][data.slot] = data.value;

                this.$emit('update', this.days);
            }
        }
    };
</script>

<style lang="scss">
    @import '../../../sass/_colors';

    .form-group {
        flex-wrap: wrap;
    }

    .listing-dates__date-wrap,
    .listing-dates__add-date {
        flex: 0 0 calc(50% - 40px);
        margin: 20px;
    }

    .listing-dates__add-date {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f3f3f3;
    }

    .listing-dates__date {
        display: flex;
        align-items: center;


        .listing-dates__start,
        .listing-dates__end {
            flex: 0 0 calc(45% - 10px);
            margin: 0 5px;
        }

        .remove-day {
            margin-top: 34px;
            opacity: 0;
            color: $gray;
            border-radius: 50%;
            height: 40px;
            width: 40px;
            pointer-events: none;
            transition: 300ms ease-in-out;

            &:hover {
                background: #6b6b6b;
                color: white;
            }
        }

        &:hover {
            .remove-day {
                opacity: 1;
                pointer-events: all;
            }
        }

    }

</style>
