<template>

</template>

<script>
	export default {
		name: 'MapMarker',
		props: {
		}
	};
</script>

<style scoped>

</style>
// Define a variable holding SVG mark-up that defines an icon image:
var svgMarkup = '';

// Create an icon, an object holding the latitude and longitude, and a marker:
var icon = new H.map.Icon(svgMarkup),
coords = {lat: 52.53075, lng: 13.3851},
marker = new H.map.Marker(coords, {icon: icon});
