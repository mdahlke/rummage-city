<template>

    <div class="container mt-5 mb-5">

        <div class="row">
            <div class="col-12">
                <router-link :to="{ name: 'dashboard' }">&larr; Back to dashboard</router-link>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- we prevent the submit on the form because of DropzoneJS -->
                <form @submit="submitForm"
                      id="listing-edit"
                      class="form"
                      method="post"
                      :action="(isNew && listing ? '/api/listings' : '/api/listings/' + listing.id)"
                >
                    <input type="hidden" name="_method" :value="(isNew ? 'post' : 'patch')"/>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="title" class="form-control" type="text" name="title" v-model="title">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <ckeditor :editor="editor"
                                  v-model="description"
                                  :config="editorConfig"
                        />
                        <input v-model="description"
                               name="description"
                               type="hidden"
                        />
                    </div>

                    <div class="form-group">
                        <MapGeocode v-if="loaded"
                                    @geocode="geocode_result"
                                    :address="address"
                                    :house="house_number"
                                    :street="street_name"
                                    :city="city"
                                    :state="state"
                                    :postcode="postcode"
                                    :country="country"
                                    :latitude="latitude"
                                    :longitude="longitude"
                                    label="Address"/>
                    </div>

                    <template v-if="loaded">
                        <ListingDatesInput :dates="dates"
                                           @update="updateDates"
                        />
                    </template>

                    <div class="text-right">
                        <button id="submit-listing"
                                type="submit"
                                class="btn btn-primary">
                            <strong>Next</strong>
                            <br/>
                            <small>Add Images</small>
                        </button>
                    </div>

                </form>

                <div v-if="!loaded"
                     id="loading-form"
                >
                    <p>Loading your listing</p>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {axiosOne, serializeArray} from '../helpers';
    import {ajaxForm} from '../form-helper';
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    const ListingDatesInput = () => import('../components/listings/ListingDatesInput.vue'/* webpackChunkName: "js/chunks/listing-dates-input" */);
    const ListingImageInput = () => import('../components/listings/ListingImageInput.vue'/* webpackChunkName: "js/chunks/listing-images-input" */);
    const MapGeocode = () => import('../components/Map/Geocode.vue'/* webpackChunkName: "js/chunks/map-geocode" */);

    export default {
        name: 'ListingEdit',
        components: {
            ListingDatesInput,
            ListingImageInput,
            MapGeocode,
            ckeditor: CKEditor.component
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
                editor: ClassicEditor,
                editorConfig: {
                    // plugins: [
                    // ],
                    toolbar: {
                        items: [
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'numberedList',
                            'bulletedList',
                            'undo',
                            'redo',
                            'removeFormat'
                        ]
                    }
                }
            };
        },
        props: {
            isNew: false,
            userListing: {
                type: Object,
                required: false
            }
        },
        created() {
            console.log('this.$route.params.id', this.$route.params.id);
            if ((this.$route.params.id)) {
                this.loadListing(this.$route.params.id).then(res => {
                    if (typeof res.data.errors !== 'undefined') {
                        return;
                    }

                    this.listing = res.data.data.userListings;

                    this.mapData(this.listing);

                    this.loaded = true;
                });
            } else {
                this.listing = this.userListing;

                if (this.listing) {
                    this.mapData(this.data);
                }
            }
        },
        methods: {
            loadListing(id) {
                return axiosOne({
                    url: '/graphql',
                    type: 'get',
                    params: {
                        query: `
							query FetchListing {
							  userListings(id: "` + id + `") {
							      id
							      title
							      description
							      address
							      house_number
							      street_name
							      city
							      state
							      postcode
							      country_code
							      latitude
							      longitude
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
					`
                    }
                });
            },
            mapData(listing) {
                this.title = listing.title;
                this.description = listing.description;
                this.address = listing.address;
                this.house_number = listing.house_number;
                this.street_name = listing.street_name;
                this.city = listing.city;
                this.state = listing.state;
                this.postcode = listing.postcode;
                this.country = listing.country_code;
                this.latitude = listing.latitude;
                this.longitude = listing.longitude;
                this.dates = listing.date;
                this.images = listing.image;
                this.description = listing.description;
            },
            updateDates(dates) {
                this.dates = dates;
            },
            updateImages(images) {
                this.images = images;
            },
            submitForm(e) {
                e.preventDefault();

                // Gets triggered when the form is actually being sent.
                const form = document.getElementById('listing-edit');

                ajaxForm(form).then(res => {
                    console.log({res});

                    if (res.data.status) {
                        this.$notification.success('Listing saved!', {
                            message: 'Your listing information has been saved.',
                            timer: 5
                        });
                    }

                    this.$router.push({name: 'listing.edit.images', params: {id: res.data.id}});
                });

            },
            geocode_result(address) {
                // this.address = address.address;
                // this.house_number = address.house_number;
                // this.street_name = address.street_name;
                // this.city = address.city;
                // this.postcode = address.postcode;
                // this.country = address.country;
                // this.latitude = address.latitude;
                // this.longitude = address.longitude;
            }
        }
    };
</script>

<style lang="scss" scoped>

    .dropzone {
        border: none;
    }

    #loading-form {
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 0;
        left: 0;
        background-color: transparentize(#fff, .05);
        font-size: 20px;
        height: 100%;
        width: 100%;
        z-index: 9;
    }

    textarea {
        min-height: 200px;
    }
</style>
