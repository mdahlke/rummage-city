<template>

    <div class="container mt-5 mb-5">

        <div class="row">
            <div class="col-12">
                <router-link :to="{ name: 'dashboard' }">&larr; Back to dashboard</router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <form @prevent.submit
                      id="listing-edit-dropzone"
                      class="form dropzone"
                      action="/listings/edit"
                      method="post"
                >
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="title" class="form-control" type="text" name="title" v-model="title">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" class="form-control"
                                  name="description">{{ description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <MapGeocode @geocode="geocode_result"
                                    :address="address"
                                    :house="house_number"
                                    :street="street_name"
                                    :city="city"
                                    :state="state"
                                    :postcode="postcode"
                                    :country="country"
                                    :latitude="latitude"
                                    :longitude="longitude"/>
                    </div>

                    <template v-if="loaded">
                        <ListingDatesInput :dates="dates" @update="updateDates"></ListingDatesInput>
                        <ListingImageInput :images="images" :max_file_size="10"></ListingImageInput>
                    </template>

                </form>

                <div class="text-right">
                    <button @click="submitForm"
                            id="submit-listing"
                            type="button"
                            class="btn btn-primary">
                        {{ isNew ? 'Add Listing' : 'Update Listing' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {axiosOne} from '../helpers';

    const ListingDatesInput = () => import('../components/listings/ListingDatesInput.vue'/* webpackChunkName: "js/chunks/listing-dates-input" */);
    const ListingImageInput = () => import('../components/listings/ListingImageInput.vue'/* webpackChunkName: "js/chunks/listing-images-input" */);
    const MapGeocode = () => import('../components/Map/Geocode.vue'/* webpackChunkName: "js/chunks/map-geocode" */);

    export default {
        name: 'ListingEdit',
        components: {
            ListingDatesInput,
            ListingImageInput,
            MapGeocode
        },
        data() {
            return {
                loaded: false,
                listing: {},
                title: '',
                description: '',
                address: '',
                house_number: '',
                street_name: '',
                city: '',
                state: '',
                postcode: '',
                country: '',
                latitude: 0,
                longitude: 0,
                dates: [],
                images: [],
            };
        },
        props: {
            isNew: false,
            userListing: {
                type: Object,
                required: false
            }
        },
        computed: {
            // address() {
            //     return {
            //         fullAddress: this.listing.address,
            //         street: this.listing.street_name,
            //         city: this.listing.city,
            //         postcode: this.listing.postcode,
            //         country: this.listing.country,
            //         latitude: this.listing.latitude,
            //         longitude: this.listing.longitude,
            //     };
            // }
        },
        created() {
            if ((this.$route.params.id || false)) {
                this.loadListing().then(res => {
                    this.listing = res.data.data.listings.data[0];

                    this.title = this.listing.title;
                    this.description = this.listing.description;
                    this.address = this.listing.address;
                    this.house_number = this.listing.number;
                    this.street_name = this.listing.name;
                    this.city = this.listing.city;
                    this.state = this.listing.state;
                    this.postcode = this.listing.postcode;
                    this.country = this.listing.country;
                    this.latitude = this.listing.latitude;
                    this.longitude = this.listing.longitude;
                    this.dates = this.listing.date;
                    this.images = this.listing.image;
                    this.description = this.listing.description;

                    console.log(this.listing, this.dates);

                    this.loaded = true;
                });
            } else {
                this.listing = this.userListing;

                if (this.listing) {
                    this.title = this.listing.title;
                    this.description = this.listing.description;
                    this.address = this.listing.address;
                    this.house_number = this.listing.number;
                    this.street_name = this.listing.name;
                    this.city = this.listing.city;
                    this.state = this.listing.state;
                    this.postcode = this.listing.postcode;
                    this.country = this.listing.country;
                    this.latitude = this.listing.latitude * 1;
                    this.longitude = this.listing.longitude * 1;
                    this.dates = this.listing.date;
                    this.images = this.listing.image;
                    this.description = this.listing.description;
                    this.loaded = true;
                }
            }
        },
        methods: {
            loadListing() {
                return axiosOne({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
							query FetchListing {
							  listings(id: "` + this.$route.params.id + `", limit: 1) {
							    data {
							      id
							      title
							      description
							      address
							      latitude
							      longitude
							      isSaved
							      saveUrl
							      removeSavedUrl
							      date {
							        start
							        end
							      }
							      image {
							        name
							        url
							      }
							    }
							  }
							}
					`
                    }
                });
            },
            updateDates(val) {
                console.log({val});
                this.dates = val;
            },
            submitForm() {
                console.log(this.$data);
            },
            geocode_result(address) {
                console.log({address});
                this.address = address.address;
                this.house_number = address.house_number;
                this.street_name = address.street_name;
                this.city = address.city;
                this.postcode = address.postcode;
                this.country = address.country;
                this.latitude = address.latitude;
                this.longitude = address.longitude;
            }
        }
    };
</script>

<style scoped>

    .dropzone {
        border: none;
    }

</style>
