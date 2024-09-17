<template>
	<div class="web-box" id="mapSection">
		<div id="mapImageContainer">
			<img :src="this.map.fileName" alt="Map Image" id="mapImage">
		</div>

		<div id="mapEditSection">
			<h2>Map Icons</h2>
			<small>Click an icon to add it to the map. Then click and drag to position the icon.</small>

			<div id="mapIcons">
				<img v-for="(icon, i) in this.icons" :src="icon.fileName" alt="Map Icon Image" class="mapIcon" @click="this.createIcon($event)">
			</div>

			<form class="data-form" >
				<label for="size">Size</label>
				<input type="number" name="size" @change="this.saveIconData($event)">

				<label for="title">Title</label>
				<input type="text" name="title" @change="this.saveIconData($event)">

				<label for="description">Description</label>
				<textarea name="description" @change="this.saveIconData($event)"></textarea>

				<label for="start-time">Start Time</label>
				<input type="time" name="start-time" @change="this.saveIconData($event)">

				<label for="end-time">End Time</label>
				<input type="time" name="end-time" @change="this.saveIconData($event)">
			</form>

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
				selectedIcon: null,
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
				newDiv.dataset.id = count;
				newDiv.dataset.width = null;
				newDiv.dataset.height = null;
				newDiv.dataset.title = null;
				newDiv.dataset.description = null;
				newDiv.dataset.startTime = null;
				newDiv.dataset.endTime = null;

				let newImg = document.createElement('img');
				newImg.src = event.target.src;

				let sizeHandle = document.createElement('i');
				sizeHandle.className = 'sizeHandle fa-solid fa-up-right-and-down-left-from-center fa-rotate-90';
				sizeHandle.id = 'sizeHandle';

				// let deleteButton = document.createElement('i');
				// deleteButton.className = 'deleteButton fa-solid fa-trash';
				// deleteButton.id = 'deleteButton';

				// let titleInput = document.createElement('input');
				// titleInput.className = 'titleInput';
				// titleInput.id = 'titleInput';
				// titleInput.type = 'text';
				// titleInput.placeholder = 'Title';

				// let timeInput = document.createElement('input');
				// timeInput.className = 'timeInput';
				// timeInput.id = 'timeInput';
				// timeInput.type = 'time';

				// let sizeHandleInput = document.createElement('input');
				// sizeHandleInput.className = 'sizeHandleInput';
				// sizeHandleInput.id = 'sizeHandleInput';
				// sizeHandleInput.type = 'number';
				// sizeHandleInput.value = 100;

				newDiv.appendChild(newImg);
				newDiv.appendChild(sizeHandle);
				// newDiv.appendChild(deleteButton);
				// newDiv.appendChild(titleInput);
				// newDiv.appendChild(timeInput);
				// newDiv.appendChild(sizeHandleInput);
				primary.appendChild(newDiv);

				let newDiv2 = document.getElementById('icon-' + count);
				newDiv2.addEventListener("mousedown", this.mouseDown);

				// let deleteButton2 = newDiv2.querySelector('.deleteButton');
				// deleteButton2.addEventListener("click", this.deleteIcon);

				// let sizeHandleInput2 = newDiv2.querySelector('.sizeHandleInput');
				// sizeHandleInput2.addEventListener("change", this.inputSizeChange);
			},

			selectIcon(event) {
				let selected = document.querySelector('.mapIcon.selected');
				
				if (selected) {
					selected.classList.remove('selected');
				}
				
				if (!selected || selected !== event.target.parentElement) {
					let target = event.target.parentElement;

					this.selectedIcon = target.dataset.id;

					target.classList.add('selected');

					let sizeInput = document.querySelector('#mapEditSection input[name="size"]');
					let titleInput = document.querySelector('#mapEditSection input[name="title"]');
					let descriptionInput = document.querySelector('#mapEditSection textarea[name="description"]');
					let startTimeInput = document.querySelector('#mapEditSection input[name="start-time"]');
					let endTimeInput = document.querySelector('#mapEditSection input[name="end-time"]');

					sizeInput.value = target.dataset.height;
					titleInput.value = target.dataset.title;
					descriptionInput.value = target.dataset.description;
					startTimeInput.value = target.dataset.startTime;
					endTimeInput.value = target.dataset.endTime;
				}
			},

			saveIconData(event) {
				if (this.selectedIcon != null) {
					let icon = document.querySelector('#mapImageContainer #icon-' + this.selectedIcon);
					icon.dataset[event.target.name] = event.target.value;
				}
			},

			mouseDown(event) {
				this.selectIcon(event);

				if (!(event.target.tagName === 'IMG' || event.target.id === 'sizeHandle')) {
					return;
				}

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

				target.dataset.height = newHeight;
				target.dataset.width = newHeight;
				
				let sizeInput = document.querySelector('#mapEditSection input[name="size"]');
				sizeInput.value = newHeight;
			},

			sizeUp(event) {
				document.removeEventListener("mousemove", this.sizeMove);
				document.removeEventListener("mouseup", this.sizeUp);
			},

			inputSizeChange(event) {
				let target = event.target.parentElement;
				let image = target.querySelector('img');
				let parent = target.parentElement;

				let aspectRatio = image.offsetWidth / image.offsetHeight;

				let newWidth = event.target.value;
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
