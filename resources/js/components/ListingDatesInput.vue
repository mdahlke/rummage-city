<template>
	<section id="listing-dates__form">
		<div class="form-group d-flex">
			<div v-for="(day, index) in days" class="listing-dates__date-wrap">
				<h3>Date {{(index+1)}}</h3>
				<dateinput :key="day.id"
				           :day="day"
				           :start_date="day.start"
				           :end_date="day.end"
				           v-model="days"
				           :class="'date-input'"
				           v-on:remove="remove_date(day)"
				           v-on:update="(...args) => update_day(day, ...args)"></dateinput>
			</div>
			<div class="listing-dates__add-date">
				<i class="fad fa-calendar"></i>
				<br />
				<button class="btn btn-link" type="button" @click="add_date()"><i class="far fa-plus"></i> Add date
				</button>
			</div>
		</div>
	</section>
</template>

<script>
	import dateinput from './DateInput.vue';
	import moment from 'moment';
	import '../../sass/component/listing-dates-input.scss';
	
	export default {
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
				default: () => {
					return [];
				}
			}
		},
		mounted() {
			this.min_date_time = moment(new Date()).format();
			
			for (let i in this.dates) {
				if (this.dates.hasOwnProperty(i)) {
					let d = this.dates[i];
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
			update_day(day, data) {
				this.days[this.days.indexOf(day)][data.slot] = data.value;
			}
		},
		components: {
			dateinput
		}
	};
</script>
