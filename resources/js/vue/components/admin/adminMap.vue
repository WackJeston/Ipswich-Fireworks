<template>
	<div class="web-box" id="mapSection">
		<div id="mapImageContainer">
			<img :src="this.map.fileName" alt="Map Image" id="mapImage">
		</div>

		<div id="mapIconsSection">
			<h2>Map Icons</h2>
			<small>Click an icon to add it to the map. Then click and drag to position the icon.</small>
			<div id="mapIcons">
				<img v-for="(icon, i) in this.icons" :src="icon.fileName" alt="Map Icon Image" class="mapIcon" @click="this.createIcon($event)">
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
				let primary = document.getElementById('mapImageContainer');
				let count = primary.children.length;

				let newDiv = document.createElement('div');
				newDiv.id = 'icon-' + count;
				newDiv.style.position = 'absolute';
				newDiv.style.top = 0;
				newDiv.style.left = 0;
				newDiv.setAttribute('draggable', true);

				let newImg = document.createElement('img');
				newImg.src = event.target.src;
				newImg.style.width = '100px';
				newImg.style.height = '100px';

				newDiv.appendChild(newImg);
				primary.appendChild(newDiv);

				let newDiv2 = document.getElementById('icon-' + count);
				newDiv2.addEventListener("dragstart", (event) =>
					event.dataTransfer.setData("text/plain", "This text may be dragged"),
				);

				console.log(newDiv2);
			},

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
