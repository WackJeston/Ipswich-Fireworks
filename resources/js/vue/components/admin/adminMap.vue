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

		data() {
			return {
				startX: 0,
				startY: 0,
				newX: 0,
				newY: 0,
				targetId: null,
			};
		},

		methods: {
			createIcon(event) {
				let primary = document.getElementById('mapImageContainer');
				let count = primary.children.length;

				let newDiv = document.createElement('div');
				newDiv.id = 'icon-' + count;
				newDiv.style.position = 'absolute';
				newDiv.style.cursor = 'grab';
				newDiv.style.top = 0;
				newDiv.style.left = 0;
				newDiv.setAttribute('draggable', true);

				let newImg = document.createElement('img');
				newImg.src = event.target.src;
				newImg.style.width = '100px';
				newImg.style.height = '100px';

				let sizeHandle = document.createElement('i');
				sizeHandle.className = 'fas fa-expand-arrows-alt';
				sizeHandle.style.position = 'absolute';
				sizeHandle.style.bottom = '4px';
				sizeHandle.style.right = '4px';
				sizeHandle.style.fontSize = '1rem';
				sizeHandle.style.color = 'white';
				sizeHandle.style.cursor = 'nwse-resize';


				newDiv.appendChild(newImg);
				newDiv.appendChild(sizeHandle);
				primary.appendChild(newDiv);

				let newDiv2 = document.getElementById('icon-' + count);
				newDiv2.addEventListener("mousedown", this.mouseDown);
			},

			mouseDown(event) {
				if (event.target.tagName === 'IMG') {
					event.preventDefault();

					this.targetId = event.target.parentElement.id;

					this.startX = event.clientX;
					this.startY = event.clientY;

					document.addEventListener("mousemove", this.mouseMove);
					document.addEventListener("mouseup", this.mouseUp);
				}
			},

			mouseMove(event) {
				let primaryImage = document.getElementById('mapImage').getBoundingClientRect();
				let target = document.getElementById(this.targetId);
				let targetPosition = target.getBoundingClientRect();

				let x = targetPosition.x - primaryImage.x;
				let y = targetPosition.y - primaryImage.y;

				let maxX = primaryImage.width - targetPosition.width;
				let maxY = primaryImage.height - targetPosition.height;

				let targetX = target.offsetLeft - (this.startX - event.clientX);
				let targetY = target.offsetTop - (this.startY - event.clientY);

				if ((x >= 0 && x <= maxX) || (x < 1 && targetX > 0) || (x > (maxX - 1) && targetX < maxX)) {
					this.newX = this.startX - event.clientX;
					this.startX = event.clientX;
					target.style.left = (target.offsetLeft - this.newX) + "px";
				}

				if ((y >= 0 && y <= maxY) || (y < 1 && targetY > 0) || (y > (maxY - 1) && targetY < maxY)) {					
					this.newY = this.startY - event.clientY;
					this.startY = event.clientY;
					target.style.top = (target.offsetTop - this.newY) + "px";
				}
			},

			mouseUp(event) {
				document.removeEventListener("mousemove", this.mouseMove);
				document.removeEventListener("mouseup", this.mouseUp);
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
