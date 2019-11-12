<template>
	<div class="here-map">
		<div ref="map"></div>
	</div>
</template>

<script>
	require('../../helpers');

	let map;
	export default {
		name: 'ListingsMap',
		data() {
			return {
				map: {},
				platform: {},
				mapEvents: {},
				data_points: [],
				icon: null,
				listing_ids: []
			};
		},
		props: {
			listings: Array,
			appId: {
				type: String,
				default: 'lQPofquBtIF1aAOEarx7'
			},
			appCode: {
				type: String,
				default: '28icBNVvG484yi09NA2c1g'
			},
			lat: {
				type: String,
				default: '43.75171'
			},
			lng: {
				type: String,
				default: '-88.44867'
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

		},
		mounted() {
			this.map = new H.Map(
				this.$refs.map,
				this.platform.createDefaultLayers().vector.normal.map,
				{
					zoom: 10,
					center: {lng: this.lng, lat: this.lat}
				});
			this.mapEvents = new H.mapevents.MapEvents(this.map);

			// Instantiate the default behavior, providing the mapEvents object:
			this.behavior = new H.mapevents.Behavior(this.mapEvents);

			// Get the default map types from the Platform object:
			var defaultLayers = this.platform.createDefaultLayers();
			// Create the default UI:
			var ui = H.ui.UI.createDefault(this.map, defaultLayers);

			let l;
			for (let i in this.listings) {
				if (this.listings.hasOwnProperty(i)) {
					l = this.listings[i];

					if (l) {
						this.listing_ids.push(l.id);
						this.data_points.push(new H.clustering.DataPoint(l.latitude, l.longitude));
						// this.add_marker({
						// 	lat: l.latitude,
						// 	lng: l.longitude
						// });
					}
				}
			}

			var clusteredDataProvider = new H.clustering.Provider(this.data_points, {
				min: 2,
				// max: 10,
				clusteringOptions: {
					eps: 32,
					minWeight: 3
				}
			});

			// Create a layer that includes the data provider and its data points:
			var layer = new H.map.layer.ObjectLayer(clusteredDataProvider);

			// Add the layer to the map:
			this.map.addLayer(layer);

			this.map.addEventListener('dragend', (evt) => {
				let data = this.map.getViewModel().getLookAtData().bounds.getBoundingBox();
				const nw = data.getTopLeft();
				const se = data.getBottomRight();

				this.get_listings_in_bounds(nw, se).then((results) => {
					const listings = results.data.data.listings.data;
					let r;
					for (var i in listings) {
						if (results.hasOwnProperty(i)) {
							r = results[i];
							if (!this.listing_ids[r.id]) {
								this.listing_ids.push(r.id);
								this.add_marker({
									lat: r.latitude,
									lng: r.longitude
								});
							}
						}
					}
				});
			});


		},
		methods: {
			add_marker(coords = null) {
				console.log(coords);
				if (!coords.lat || !coords.lng) {
					return false;
				}
				const marker = new H.map.Marker(coords, {icon: this.icon});

				// Add the marker to the map and center the map at the location of the marker:
				this.map.addObject(marker, 'rc');
				// this.map.setCenter(coords);

				return marker;
			},
			get_listings_in_bounds(nw, se) {
				const bounds = {
					nw, se
				};
				const query = JSON.stringify(JSON.stringify(bounds));

				return axios({
					url: '/graphql',
					type: 'get',
					params: {
						query: `
							query FetchListingsInBounds {
							  listings(bounds: ` + query + `) {
							    data {
							      title
							      latitude
							      longitude
							      date {
							        start
							        end
							      }
							    }
							  }
							}
					`
					}
				});
			}
		}
	};

</script>
