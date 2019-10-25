<template>
	<div class="listings">
		<aside id="listings__sidebar">
			
			<section v-for="(listing, index) in visible_listings" class="listing">
				<h4>{{ listing.title }}</h4>
				<p>{{ listing.address }}</p>
				
				<h4>Dates:</h4>
				<ul>
					<li v-for="(date, index) in listing.date">{{ date.start }} - {{ date.end }}</li>
				</ul>
				<div class="listing__actions">
					<ul class="list-inline">
						<li class="list-inline-item">
							<span @click="save(listing)"><i class="far fa-heart"></i> Save</span>
						</li>
					</ul>
				</div>
			</section>
		</aside>
		
		<div id="listings__map"></div>
	</div>
</template>

<script>
	require('../../helpers');
	import '../../../sass/component/listings-map.scss';
	import '../../../../node_modules/mapbox-gl/dist/mapbox-gl.css';
	
	const mapboxgl = require('mapbox-gl');
	
	export default {
		name: 'ListingsMap',
		data() {
			return {
				map: {},
				platform: {},
				mapEvents: {},
				data_points: [],
				icon: null,
				listing_ids: [],
				visible_listings: []
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
			zoom: {
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
			
		},
		mounted() {
			
			if (this.listings) {
				this.visible_listings = this.listings;
			}
			
			mapboxgl.accessToken = 'pk.eyJ1IjoibWRhaGxrZSIsImEiOiJjazI2bGgzNjUwZzlsM2dxaDd2OXgxZW9yIn0.kKYT-PvLDgQeFZWc2MMOAw';
			this.map = new mapboxgl.Map({
				container: 'listings__map',
				style: 'mapbox://styles/mapbox/streets-v11',
				center: {lng: this.lng, lat: this.lat},
				zoom: this.zoom
			});
			
			
			// Add zoom and rotation controls to the map.
			this.map.addControl(new mapboxgl.NavigationControl());
			
			this.map.on('dragend', () => {
				let bounds = this.map.getBounds();
				console.log({bounds});
				// let data = this.map.getViewModel().getLookAtData().bounds.getBoundingBox();
				// const nw = data.getTopLeft();
				// const se = data.getBottomRight();
				
				this.get_listings_in_bounds(bounds).then((results) => {
					const listings = results.data.data.listings.data;
					console.log({results, listings}, this.listing_ids);
					this.visible_listings = listings;
					
					let r;
					for (let i in listings) {
						if (listings.hasOwnProperty(i)) {
							r = listings[i];
							console.log({r});
							if (!this.listing_ids.includes(r.id)) {
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
			
			let l;
			for (let i in this.listings) {
				if (this.listings.hasOwnProperty(i)) {
					l = this.listings[i];
					
					if (l.id) {
						this.listing_ids.push(l.id);
						this.add_marker({lat: l.latitude, lon: l.longitude});
					}
				}
			}
		},
		methods: {
			add_marker(coords = null) {
				if (!coords.lat || !coords.lng) {
					return false;
				}
				
				let marker = new mapboxgl.Marker()
					.setLngLat({lon: coords.lng, lat: coords.lat})
					.addTo(this.map);
				
				return marker;
			},
			get_listings_in_bounds(bounds) {
				const query = JSON.stringify(JSON.stringify({
					sw: bounds._sw,
					ne: bounds._ne
				}));
				
				return axios({
					url: '/graphql',
					type: 'get',
					params: {
						query: `
							query FetchListingsInBounds {
							  listings(bounds: ` + query + `) {
							    data {
							      id
							      title
							      address
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
			},
			save(listing) {
				console.log('save', listing.id);
			}
		}
	};

</script>
