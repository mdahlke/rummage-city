<template>
    <div class="search-box"
         :class="{ 'filters': showFilters }">
        <SearchBoxFilter v-if="showFilters"
                         @filterAdd="filterAdd"
                         @filterRemove="filterRemove"
        />

        <form class="search-form form-inline my-2 my-lg-0" :action="action">
            <div class="input-group">
                <input class="form-control search-input"
                       name="q"
                       type="search"
                       placeholder="Enter a city or keyword"
                       aria-label="Enter a city or keyword"
                       aria-describedby="search-button"
                       v-model="q"
                       v-on:keyup="search(q)"
                >
                <div class="input-group-append">
                    <button id="search-button" class="btn btn-outline-primary" type="submit">
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex';
    const SearchBoxFilter = () => import('./SearchBoxFilter'/* webpackChunkName: "js/chunks/search-box-filter" */);

    export default {
        name: 'SearchBox',
        components: {
            SearchBoxFilter
        },
        data() {
            return {
                q: '',
                action: '',
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
                return;
            }
        }
    };
</script>

<style lang="scss">
    @import '../../../node_modules/bootstrap/scss/mixins/breakpoints';
    @import '../../sass/_colors.scss';

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
