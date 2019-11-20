<template>
    <ul class="search-filters list-inline">
        <SearchBoxFilterItem v-for="(filter) in filters"
                             v-on="$listeners"
                             :key="'search-box-filter-' + filter.name"
                             :name="filter.name"
                             :text="filter.text"
                             :icon="filter.icon"
        />
    </ul>
</template>

<script>
    import SearchBoxFilterItem from './SearchBoxFilterItem';

    export default {
        name: 'SearchBoxFilter',
        components: {
            SearchBoxFilterItem
        },
        data() {
            return {
                filters: [
                    {
                        name: 'saved',
                        text: 'Saved only',
                        icon: 'fas fa-heart',
                    },
                    {
                        name: 'today',
                        text: 'Today',
                        icon: 'fas fa-clock',
                    },
                    {
                        name: 'weekend',
                        text: 'This Weekend',
                        icon: 'fas fa-calendar',
                    }
                ]
            }
        },
        methods: {}
    }
</script>

<style lang="scss">
    @import '../../sass/colors';

    .search-filters {
        margin-right: 30px;
        margin-bottom: 0;

        li {
            padding: 7px 20px;
            margin: 0 8px;
            border: 1px solid $tertiary;
            cursor: pointer;

            i {
                color: $tertiary;
            }

            &.active {
                background-color: $tertiary;
                color: $white;

                i {
                    color: $white;
                }
            }
        }
    }


    @media (max-width: 575px) {

        .search-filters {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            justify-content: space-evenly;
            margin-right: 0;
            width: 100%;

            li {
                display: block;
                flex: 1 0 calc(33% - 5px);
                padding: 7px 20px;
                margin: 0 0 8px 0;
                border: 1px solid #35495e;
                cursor: pointer;
                white-space: nowrap;
            }
        }

    }
</style>
