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

			<button @click="this.saveMap" id="mapSave" class="page-button padding-large pb-dark">Save Map</button>
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
				newDiv.className = 'mapIcon';
				newDiv.id = 'icon-' + count;
				newDiv.setAttribute('draggable', true);

				let newImg = document.createElement('img');
				newImg.src = event.target.src;

				let sizeHandle = document.createElement('i');
				sizeHandle.className = 'sizeHandle fa-solid fa-up-right-and-down-left-from-center fa-rotate-90';
				sizeHandle.id = 'sizeHandle';

				let sizeHandleInput = document.createElement('input');
				sizeHandleInput.id = 'sizeHandleInput';
				sizeHandleInput.type = 'number';
				sizeHandleInput.value = newImg.style.height;

				let deleteButton = document.createElement('i');
				deleteButton.className = 'deleteButton fa-solid fa-trash';
				deleteButton.id = 'deleteButton';

				newDiv.appendChild(newImg);
				newDiv.appendChild(sizeHandle);
				newDiv.appendChild(sizeHandleInput);
				newDiv.appendChild(deleteButton);
				primary.appendChild(newDiv);

				let newDiv2 = document.getElementById('icon-' + count);
				newDiv2.addEventListener("mousedown", this.mouseDown);

				let deleteButton2 = document.getElementById('deleteButton');
				deleteButton2.addEventListener("click", this.deleteIcon);
			},

			mouseDown(event) {
				event.preventDefault();

				this.targetId = event.target.parentElement.id;

				this.startX = event.clientX;
				this.startY = event.clientY;

				if (event.target.tagName === 'IMG') {
					document.addEventListener("mousemove", this.mouseMove);
					document.addEventListener("mouseup", this.mouseUp);
				
				} else if (event.target.id === 'sizeHandle') {
					document.addEventListener("mousemove", this.sizeMove);
					document.addEventListener("mouseup", this.sizeUp);
				}
			},

			mouseMove(event) {
				let primaryImage = document.getElementById('mapImage').getBoundingClientRect();
				let target = document.getElementById(this.targetId);
				let targetPosition = target.getBoundingClientRect();

				let maxX = primaryImage.width - targetPosition.width;
				let maxY = primaryImage.height - targetPosition.height;

				let targetX = target.offsetLeft - (this.startX - event.clientX);
				let targetY = target.offsetTop - (this.startY - event.clientY);

				// Ensure the new position does not exceed the parent's borders
				if (targetX < 0) {
					targetX = 0;
				} else if (targetX > maxX) {
					targetX = maxX;
				}

				if (targetY < 0) {
					targetY = 0;
				} else if (targetY > maxY) {
					targetY = maxY;
				}

				target.style.left = targetX + "px";
				target.style.top = targetY + "px";

				this.startX = event.clientX;
				this.startY = event.clientY;
			},

			mouseUp(event) {
				document.removeEventListener("mousemove", this.mouseMove);
				document.removeEventListener("mouseup", this.mouseUp);
			},

			sizeMove(event) {
				let target = document.getElementById(this.targetId);
				let image = target.querySelector('img');
				let parent = target.parentElement;

				this.newX = this.startX - event.clientX;
				this.startX = event.clientX;

				this.newY = this.startY - event.clientY;
				this.startY = event.clientY;

				let aspectRatio = image.offsetWidth / image.offsetHeight;

				let newWidth = image.offsetWidth - this.newX;
				let newHeight = newWidth / aspectRatio;

				// Ensure the new dimensions do not exceed the parent's borders
				if (newWidth > parent.clientWidth) {
					newWidth = parent.clientWidth;
					newHeight = newWidth / aspectRatio;
				}

				if (newHeight > parent.clientHeight) {
					newHeight = parent.clientHeight;
					newWidth = newHeight * aspectRatio;
				}

				// Ensure the new dimensions do not fall below the minimum size
				const minSize = 30;
				if (newWidth < minSize) {
					newWidth = minSize;
					newHeight = newWidth / aspectRatio;
				}

				if (newHeight < minSize) {
					newHeight = minSize;
					newWidth = newHeight * aspectRatio;
				}

				image.style.width = newWidth + "px";
				image.style.height = newHeight + "px";
			},

			sizeUp(event) {
				document.removeEventListener("mousemove", this.sizeMove);
				document.removeEventListener("mouseup", this.sizeUp);
			},

			deleteIcon(event) {
				let target = event.target.parentElement;
				target.remove();
			},

			async saveMap() {
				let primary = document.getElementById('mapImage');

				let config = {
					asset: this.map.assetId,
					canvas: {
						height: primary.offsetHeight,
						width: primary.offsetWidth
					},
					images: []
				};

				let icons = primary.parentElement.children;

				for (let i = 0; i < icons.length; i++) {
					let icon = icons[i];

					if (icon.tagName !== 'IMG') {
						let iconImage = icon.querySelector('img');

						let iconSize = {
							height: iconImage.offsetHeight,
							width: iconImage.offsetWidth
						};

						let iconPosition = {
							top: icon.offsetTop,
							left: icon.offsetLeft
						};

						config.images.push({
							asset: iconImage.getAttribute('src'),
							size: iconSize,
							position: iconPosition
						});
					}
				}
				
				console.log(encodeURIComponent(config));
				console.log(JSON.stringify(config));

				// try {
				// 	// this.response = await fetch(`/admin-mapSave/${encodeURIComponent(config)}/`);
				// 	this.response = await fetch(`/admin-mapSave/${encodeURIComponent(config)}`, {
				// 		// method: "POST", 
				// 		// body: JSON.stringify(config),
				// 		headers: { 
				// 			"Content-type": "application/json; charset=UTF-8"
				// 		}
				// 	});
				// 	this.result = await this.response.json();
					
				// } catch (err) {
				// 	console.log('----ERROR----');
				// 	console.log(err);
				// } finally {
				// 	console.log('----FINALLY----');
				// 	console.log(this.result);
				// }
			}
		},
  };
</script>
