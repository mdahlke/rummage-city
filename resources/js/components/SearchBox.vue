<template>
    <form class="form-inline my-2 my-lg-0" :action="action">
        <input class="form-control mr-sm-2" name="q" type="search" placeholder="Search" aria-label="Search"
               v-model="q" v-on:keyup="search(q)">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
</template>

<script>
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
            route: String
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
            search(query) {
                console.log({query});
            }
        }
    }
</script>

<style scoped>

</style>
