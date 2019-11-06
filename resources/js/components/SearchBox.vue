<template>
    <div class="search-box"
         :class="{ 'filters': showFilters }">
        <ul v-if="showFilters" class="search-filters list-inline">
            <li class="list-inline-item"><i class="fas fa-heart"></i> Saved only</li>
            <li class="list-inline-item"><i class="fas fa-clock"></i> Today</li>
            <li class="list-inline-item"><i class="fas fa-calendar"></i> This weekend</li>
        </ul>

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
    import '../../sass/component/search-box.scss';

    export default {
        name: "SearchBox",
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
        mounted() {
            if (this.query) {
                this.q = this.query;
            }
            if (this.route) {
                this.action = this.route;
            }

            console.log(this.showFilters);
        },
        methods: {
            search(query) {
                console.log({query});
            }
        }
    }
</script>
