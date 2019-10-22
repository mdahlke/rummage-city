<template>
	<div class="geocode-input">
		<input id="address"
		       class="form-control"
		       type="text"
		       name="address"
		       :value="addr"
		       @change="geocode">
		<input type="hidden"
		       name="latitude"
		       :value="lat">
		<input type="hidden"
		       name="longitude"
		       :value="lng">
		<div class="here-map">
			<div ref="map" v-bind:style="{ width: width, height: height }"></div>
		</div>
	</div>
</template>

<script>
	import helpers from '../../helpers';
	
	export default {
		name: 'GeocodeMap',
		data() {
			return {
				map: {},
				platform: {},
				mapEvents: {},
				geocoder: {},
				behavior: {},
				addr: '',
				lat: '37.7397',
				lng: '-121.4252',
				icon: null
			};
		},
		props: {
			address: String,
			latitude: String,
			longitude: String,
			appId: {
				type: String,
				default: 'lQPofquBtIF1aAOEarx7'
			},
			appCode: {
				type: String,
				default: '28icBNVvG484yi09NA2c1g'
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
				default: ''
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
			this.geocoder = this.platform.getGeocodingService();
			
		},
		mounted() {
			if (this.latitude) {
				this.lat = this.latitude;
			}
			if (this.longitude) {
				this.lng = this.longitude;
			}
			if (this.address) {
				this.addr = this.address;
			}
			
			this.map = new H.Map(
				this.$refs.map,
				this.platform.createDefaultLayers().vector.normal.map,
				{
					zoom: 17,
					center: {lng: this.lng, lat: this.lat}
				});
			this.mapEvents = new H.mapevents.MapEvents(this.map);
			
			// Instantiate the default behavior, providing the mapEvents object:
			this.behavior = new H.mapevents.Behavior(this.mapEvents);
			
			// Get the default map types from the Platform object:
			var defaultLayers = this.platform.createDefaultLayers();
			// Create the default UI:
			var ui = H.ui.UI.createDefault(this.map, defaultLayers);
			
			if (this.lat && this.lng) {
				this.add_marker({lng: this.lng, lat: this.lat});
			}
			
		},
		methods: {
			add_marker(coords = null) {
				const marker = new H.map.Marker(coords, {icon: this.icon});
				
				// Add the marker to the map and center the map at the location of the marker:
				this.map.addObject(marker, 'rc');
				this.map.setCenter(coords, 'rc');
			},
			
			// Define a callback function to process the geocoding response:
			geocode(address) {
				// Call the geocode method with the geocoding parameters,
				// the callback and an error callback function (called if a
				// communication error occurs):
				this.geocoder.geocode({searchText: address.target.value}, (result) => {
					let locations = result.Response.View[0].Result,
						position,
						marker,
						i;
					// Add a marker for each location found
					for (i = 0; i < locations.length; i++) {
						position = {
							lat: locations[i].Location.DisplayPosition.Latitude,
							lng: locations[i].Location.DisplayPosition.Longitude
						};
						this.lat = position.lat;
						this.lng = position.lng;
						this.add_marker(position);
					}
				}, function (e) {
					console.log(e);
				});
			}
			
		}
		// var platform = new H.service.Platform({
		// 	'apikey': '7W5ZSgnP_hvci-01R4EbN1_T17e_5x1zVr54MheJxTk\t}'
		// });
	};

</script>

<style scoped>

</style>
