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
                                    :country="country_code"
                                    :latitude="latitude"
                                    :longitude="longitude"/>
                    </div>

                    <ListingDatesInput :dates="dates"></ListingDatesInput>

                    <ListingImageInput :images="images" :max_file_size="10"></ListingImageInput>

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
                listing: {},
                title: '',
                description: '',
                address: '',
                house_number: '',
                street_name: '',
                city: '',
                state: '',
                postcode: '',
                country_code: '',
                latitude: '',
                longitude: '',
                dates: '',
                images: '',
                description: '',
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
            address() {
                return {
                    fullAddress: this.listing.address,
                    street: this.listing.street_name,
                    city: this.listing.city,
                    postcode: this.listing.postcode,
                    country: this.listing.country,
                    latitude: this.listing.latitude,
                    longitude: this.listing.longitude,
                };
            }
        },
        created() {
            if (!this.userListing) {
                this.listing = this.$store.getters.getUserListing(this.$route.params.id);
            } else {
                this.listing = this.userListing;
            }

            if (this.listing) {
                this.title = this.listing.title;
                this.description = this.listing.description;
                this.address = this.listing.address;
                this.house_number = this.listing.number;
                this.street_name = this.listing.name;
                this.city = this.listing.city;
                this.state = this.listing.state;
                this.postcode = this.listing.postcode;
                this.country_code = this.listing.code;
                this.latitude = this.listing.latitude;
                this.longitude = this.listing.longitude;
                this.dates = this.listing.dates;
                this.images = this.listing.images;
                this.description = this.listing.description;
            }
        },
        methods: {
            submitForm() {
                console.log(this.$data);
            },
            geocode_result(address) {
                console.log({address});
                this.listing.address = address.address;
                this.listing.house_number = address.house_number;
                this.listing.street_name = address.street_name;
                this.listing.city = address.city;
                this.listing.postcode = address.postcode;
                this.listing.country = address.country;
                this.listing.latitude = address.latitude;
                this.listing.longitude = address.longitude;
            }
        }
    };
</script>

<style scoped>

</style>
I
