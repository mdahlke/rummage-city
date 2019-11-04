<template>
	<div class="listing-dates__date">
		<div class="listing-dates__start">
			<label>Start Date/Time</label>
			<VueCtkDateTimePicker v-model="start"
			                      :id="'start-date-'+this.$vnode.key"
			                      :minute-interval="15"
			                      :min-date="now()"
			                      :disabled-hours="disabled_hours"
			                      name="start[]"></VueCtkDateTimePicker>
		</div>
		<div class="listing-dates__end">
			<label>End Date/Time</label>
			<VueCtkDateTimePicker v-model="end"
			                      :minute-interval="15"
			                      :min-date="end_min_date"
			                      :disabled-hours="disabled_hours"
			                      name="end[]"></VueCtkDateTimePicker>
		</div>
		<button class="btn btn-link remove-day"
		        type="button"
		        v-on:click="remove(day)"
		        :data-index="this.$vnode.key" title="Remove day" aria-label="delete"><i class="fad fa-trash"></i>
		</button>
	</div>
</template>

<script>
	import moment from 'moment';
	import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
	
	export default {
		data() {
			return {
				start: moment(new Date()).format(),
				end: moment(new Date()).add(2, 'days').format(),
				is_changed: false
			};
		},
		props: {
			start_date: String,
			end_date: String,
			day: Object,
			disabled_hours: {
				type: Array,
				default: () => {
					return ['00', '01', '02', '03', '04', '22', '23'];
				}
			}
		},
		mounted() {
			if (this.start_date) {
				this.start = this.start_date;
				this.is_changed = true;
			}
			if (this.end_date) {
				this.end = this.end_date;
				this.is_changed = true;
			}
		},
		computed: {
			end_min_date() {
				let min = moment(this.start).format() || moment(new Date()).format();
				
				return min;
			}
		},
		methods: {
			now() {
				return moment(new Date()).format();
			},
			remove(day) {
				this.$emit('remove');
			}
			
		}
	};
</script>
