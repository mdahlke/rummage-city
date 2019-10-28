<template>
    <section class="listing__popup">
        {{title}}
        <ul class="list-inline">
            <li v-for="(image, index) in listing.image" class="list-inline-item">
                <img src="image.path"/>
            </li>
        </ul>
    </section>
</template>

<script>
    export default {
        name: "MapboxPopup",
        props: {
            listing: Object
        }
    }
</script>

<style scoped>

</style>
