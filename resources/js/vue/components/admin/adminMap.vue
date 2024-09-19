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

			<form class="data-form" v-show="this.selectedIcon != null">
				<label for="size">Size</label>
				<input type="number" name="size" min="30" @change="this.saveIconData($event)" @keyup="this.saveIconData($event)">

				<label for="title">Title</label>
				<input type="text" name="title" @keyup="this.saveIconData($event)">

				<label for="description">Description</label>
				<textarea name="description" @keyup="this.saveIconData($event)"></textarea>

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

		mounted() {
			this.map.images.forEach((existingIcon) => this.createExistingIcon(existingIcon))
		},

		methods: {
			createIcon(event) {
				let primary = document.getElementById('mapImageContainer');
				let count = primary.children.length;

				let newDiv = document.createElement('div');
				newDiv.className = 'mapIcon new';
				newDiv.id = 'icon-' + count;
				newDiv.setAttribute('draggable', true);
				newDiv.dataset.id = count;
				newDiv.dataset.height = 100;
				newDiv.dataset.width = null;
				newDiv.dataset.title = '';
				newDiv.dataset.description = '';
				newDiv.dataset.startTime = null;
				newDiv.dataset.endTime = null;

				let newImg = document.createElement('img');
				newImg.src = event.target.src;

				let deleteButton = document.createElement('i');
				deleteButton.className = 'deleteButton fa-solid fa-trash';
				deleteButton.id = 'deleteButton';

				let sizeHandle = document.createElement('i');
				sizeHandle.className = 'sizeHandle fa-solid fa-up-right-and-down-left-from-center fa-rotate-90';
				sizeHandle.id = 'sizeHandle';

				newDiv.appendChild(newImg);
				newDiv.appendChild(deleteButton);
				newDiv.appendChild(sizeHandle);
				primary.appendChild(newDiv);

				let newDiv2 = document.getElementById('icon-' + count);
				newDiv2.addEventListener("mousedown", this.mouseDown);

				let deleteButton2 = newDiv2.querySelector('.deleteButton');
				deleteButton2.addEventListener("click", this.deleteIcon);
			},

			createExistingIcon(existingIcon) {
				let primary = document.getElementById('mapImageContainer');

				if (primary.offsetHeight == 0 || primary.offsetWidth == 0) {
					setTimeout(() => {
						this.createExistingIcon(existingIcon);
					}, 100);

				} else {
					let count = primary.children.length;

					let heightRatio = (primary.offsetHeight / this.map.canvasHeight);
					let widthRatio = primary.offsetWidth / this.map.canvasWidth;

					let newIconPositionTop = existingIcon.position.top * heightRatio;
					let newIconPositionLeft = existingIcon.position.left * widthRatio;
					let newIconHeight = existingIcon.size.height * heightRatio;
					let newIconWidth = existingIcon.size.width * widthRatio;
					
					let newDiv = document.createElement('div');
					newDiv.className = 'mapIcon';
					newDiv.id = 'icon-' + count;
					newDiv.setAttribute('draggable', true);
					newDiv.style.top = newIconPositionTop + 'px' ?? '0px';
					newDiv.style.left = newIconPositionLeft + 'px' ?? '0px';
					newDiv.dataset.id = count;
					newDiv.dataset.height = newIconHeight ?? 100;
					newDiv.dataset.width = newIconWidth ?? 100;
					newDiv.dataset.title = existingIcon.title;
					newDiv.dataset.description = existingIcon.description;
					newDiv.dataset.startTime = existingIcon.startTime;
					newDiv.dataset.endTime = existingIcon.endTime;

					let newImg = document.createElement('img');
					newImg.src = existingIcon.asset;
					newImg.style.width = newIconWidth + 'px' ?? '100px';
					newImg.style.height = newIconHeight + 'px' ?? '100px';

					let deleteButton = document.createElement('i');
					deleteButton.className = 'deleteButton fa-solid fa-trash';
					deleteButton.id = 'deleteButton';

					let sizeHandle = document.createElement('i');
					sizeHandle.className = 'sizeHandle fa-solid fa-up-right-and-down-left-from-center fa-rotate-90';
					sizeHandle.id = 'sizeHandle';

					newDiv.appendChild(newImg);
					newDiv.appendChild(deleteButton);
					newDiv.appendChild(sizeHandle);
					primary.appendChild(newDiv);

					let newDiv2 = document.getElementById('icon-' + count);
					newDiv2.addEventListener("mousedown", this.mouseDown);

					let deleteButton2 = newDiv2.querySelector('.deleteButton');
					deleteButton2.addEventListener("click", this.deleteIcon);
				}
			},

			selectIcon(event) {
				let selected = document.querySelector('.mapIcon.selected');
				
				if (!selected || selected !== event.target.parentElement) {
					if (selected) {
						selected.classList.remove('selected');

						let sizeHandle = selected.querySelector('#sizeHandle');
						sizeHandle.style.display = 'none';

						let deleteButton = selected.querySelector('#deleteButton');
						deleteButton.style.display = 'none';
					}

					let target = event.target.parentElement;
					target.classList.add('selected');

					let sizeHandle = target.querySelector('#sizeHandle');
					sizeHandle.style.display = 'flex';

					let deleteButton = target.querySelector('#deleteButton');
					deleteButton.style.display = 'flex';

					this.selectedIcon = target.dataset.id;

					let sizeInput = document.querySelector('#mapEditSection input[name="size"]');
					let titleInput = document.querySelector('#mapEditSection input[name="title"]');
					let descriptionInput = document.querySelector('#mapEditSection textarea[name="description"]');
					let startTimeInput = document.querySelector('#mapEditSection input[name="start-time"]');
					let endTimeInput = document.querySelector('#mapEditSection input[name="end-time"]');

					sizeInput.value = Math.round(target.dataset.height);
					titleInput.value = target.dataset.title;
					descriptionInput.value = target.dataset.description;
					startTimeInput.value = target.dataset.startTime;
					endTimeInput.value = target.dataset.endTime;
				}
			},

			saveIconData(event) {
				if (this.selectedIcon != null) {
					let icon = document.querySelector('#mapImageContainer #icon-' + this.selectedIcon);
					let name = event.target.name;

					if (name == 'size') {
						let size = this.inputSizeChange(event);

						icon.dataset.height = size[0];
						icon.dataset.width = size[1];

					} else if (name == 'start-time') {
						if (event.target.value == '') {
							icon.dataset.startTime = null;
						} else {
							icon.dataset.startTime = event.target.value;
						}

					} else if (name == 'end-time') {
						if (event.target.value == '') {
							icon.dataset.endTime = null;
						} else {
							icon.dataset.endTime = event.target.value;
						}

					} else {
						icon.dataset[name] = event.target.value;
					}
				}
			},

			inputSizeChange(event) {
				let target = document.querySelector('.mapIcon.selected');
				let parent = document.querySelector('#mapImageContainer');
				let image = target.querySelector('img');

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

				return [newHeight, newWidth];
			},

			deleteIcon(event) {
				let target = event.target.parentElement;
				target.remove();

				this.selectedIcon = null;
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
							height: icon.dataset.height,
							width: icon.dataset.width
						};

						let iconPosition = {
							top: icon.offsetTop,
							left: icon.offsetLeft
						};

						config.images.push({
							asset: iconImage.getAttribute('src'),
							size: iconSize,
							position: iconPosition,
							title: icon.dataset.title,
							description: icon.dataset.description,
							startTime: icon.dataset.startTime,
							endTime: icon.dataset.endTime
						});
					}
				}

				try {
					this.response = await fetch(`/admin-mapSave/`, {
						method: "POST",
						body: JSON.stringify(config),
						headers: {
							'Content-Type': 'application/json',
							'Accept': 'application/json',
							'url': '/admin-mapSave/',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
						},
					});
					this.result = await this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);
				}
			}
		},
  };
</script>
