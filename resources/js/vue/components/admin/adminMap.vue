<template>
	<div class="web-box" id="mapSection">
		<img :src="this.map.fileName" alt="Map Image" id="mapImage">

		<div id="mapIconsSection">
			<h2>Map Icons</h2>
			<small>Click an icon to add it to the map. Then click and drag to position the icon.</small>
			<div id="mapIcons">
				<img v-for="(icon, i) in this.icons" :src="icon.fileName" alt="Map Icon Image" id="mapIcon" @click="this.createIcon($event)">
			</div>
		</div>
	</div>
</template>


<script>
  export default {
    props: [
			'map',
			'icons',
    ],

		methods: {
			createIcon(event) {
				console.log(event.target);

				let newDiv = document.createElement('div');
				newDiv.style.position = 'absolute';
				newDiv.style.top = 0;
				newDiv.style.left = 0;
			}

			// saveMap() {
			// 	let primary = document.getElementById('mapImage');

			// 	let record = {
			// 		asset: this.map.assetId,
			// 		canvas: {
			// 			height: primary.height(),
			// 			width: primary.width()
			// 		},
			// 		images: []
			// 	};
			// }
		},
  };
</script>
