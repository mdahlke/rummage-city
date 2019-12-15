<template>

    <div class="search-results"
         :class="{'is-visible': (results.length || isSearching)}"
    >
        <template v-if="results.length">

            <SearchBoxGeocodeResult v-for="result in results"
                                    :key="result.id"
                                    :result="result"
                                    @triggerSearch="triggerSearchChild"
            />

        </template>

        <div class="is-searching"
             :class="{'is-visible': isSearching}"
        ><i class="far fa-spinner fa-spin"></i></div>
    </div>
</template>

<script>
    const SearchBoxGeocodeResult = () => import('./SearchBoxGeocodeResult'/* webpackChunkName: "js/chunks/search-box-geocode-result" */);

    export default {
        name: 'SearchBoxGeocodeResults',
        components: {
            SearchBoxGeocodeResult
        },
        props: {
            results: {
                type: Array,
                required: true,
            },
            isSearching: {
                type: Boolean,
                required: true,
            }
        },
        methods: {
            triggerSearchChild: function (result) {
                this.$emit('geocodeResultSearch', result);
            }
        }
    };
</script>

<style lang="scss">
    .search-results:not(.is-visible) {
        display: none;
    }

    .search-results {
        position: absolute;
        top: 100%;
        padding: 10px;
        background: white;
        text-align: left;
        border: 1px solid;
        border-top: 0;
        cursor: pointer;
        width: 100%;
    }

    .is-searching:not(.is-visible) {
        display: none;
    }
</style>
