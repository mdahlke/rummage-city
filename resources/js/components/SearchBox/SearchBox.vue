<template>
    <div class="search-box"
         :class="{ 'filters': showFilters }">
        <SearchBoxFilter v-if="showFilters"
                         @filterAdd="filterAdd"
                         @filterRemove="filterRemove"
        />

        <form id="geocode-form"
              class="search-form form-inline my-2 my-lg-0"
              :action="action">
            <div class="input-group">
                <input class="form-control search-input"
                       name="q"
                       type="search"
                       placeholder="Enter a city or keyword"
                       aria-label="Enter a city or keyword"
                       aria-describedby="search-button"
                       v-model="q"
                       v-on:keyup="searchDebounce"
                >
                <div class="input-group-append">
                    <button id="search-button" class="btn btn-outline-primary" type="submit">
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </div>
            <SearchBoxGeocodeResults :results="results"
                                     :isSearching="isSearching"
                                     @geocodeResultSearch="triggerSearchFromResult"/>
        </form>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex';
    import _ from 'lodash';

    const SearchBoxFilter = () => import('./SearchBoxFilter'/* webpackChunkName: "js/chunks/search-box-filter" */);
    const SearchBoxGeocodeResults = () => import('./SearchBoxGeocodeResults'/* webpackChunkName: "js/chunks/search-box-geocode-results" */);

    export default {
        name: 'SearchBox',
        components: {
            SearchBoxFilter,
            SearchBoxGeocodeResults
        },
        data() {
            return {
                q: '',
                action: '',
                results: [],
                isSearching: false,
            };
        },
        props: {
            query: String,
            route: String,
            showFilters: {
                type: Boolean,
                default: true,
            }
        },
        computed: {
            ...mapGetters([
                'searchState'
            ])
        },
        mounted() {
            if (this.query) {
                this.q = this.query;
            }
            if (this.route) {
                this.action = this.route;
            }
        },
        methods: {
            filterAdd(val) {
                this.$store.dispatch('filterAdd', val);
            },
            filterRemove(val) {
                this.$store.dispatch('filterRemove', val);
            },
            search() {
                const baseUrl = 'https://api.mapbox.com/geocoding/v5';
                const token = 'pk.eyJ1IjoibWRhaGxrZSIsImEiOiJjazI2bGgzNjUwZzlsM2dxaDd2OXgxZW9yIn0.kKYT-PvLDgQeFZWc2MMOAw';
                const uri = '/mapbox.places/' + encodeURI(this.q) + '.json?access_token=' + token;
                const url = baseUrl + uri;

                axios.get(url)
                    .then((results) => {
                        this.isSearching = false;
                        this.results = results.data.features.filter(r => r.place_type.includes('place'));
                    })
                    .catch(e => {
                        this.isSearching = false;
                    });
            },
            searchDebounce: _.debounce(function (e) {
                this.isSearching = true;
                this.search();
            }, 300),
            triggerSearchFromResult: function (result) {
                this.q = result.place_name;

                console.log(result, this.q);

                document.getElementById('search-button').click();
            },
        }
    };
</script>

<style lang="scss">
    @import '../../../../node_modules/bootstrap/scss/mixins/breakpoints';
    @import '../../../sass/colors';

    .search-form {
        position: relative;
    }

    .search-box {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        width: 100%;

        &.filters {
            justify-content: flex-end;
        }

        .search-input {
            border-color: $tertiary;
            border-right: 0;
            min-width: 300px;

            &:active,
            &:focus {
                outline: 0;
                box-shadow: none;
            }
        }

        .input-group {
            flex-wrap: nowrap;
        }

        #search-button {
            border-color: $tertiary;
            border-left: 0;
        }

    }
</style>
