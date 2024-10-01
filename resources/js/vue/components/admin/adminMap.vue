<template>
	<div class="vue-button-row">
		<div>
			<button v-if="this.active" @click="this.toggleActive()" class="page-button pb-success" type="button"><i class="fa-solid fa-toggle-on"></i>On</button>
			<button v-else @click="this.toggleActive()" class="page-button pb-danger" type="button"><i class="fa-solid fa-toggle-off"></i>Off</button>
		</div>
		<div></div>
	</div>

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

				<label for="angle">Angle</label>
				<input type="number" name="angle" min="0" max="360" @change="this.saveIconData($event)" @keyup="this.saveIconData($event)">

				<label for="title">Title</label>
				<input type="text" name="title" @keyup="this.saveIconData($event)">

				<label for="description">Description</label>
				<textarea name="description" @keyup="this.saveIconData($event)"></textarea>

				<label for="programme">Programme</label>
				<select name="programme" id="programmeSelect" @change="this.saveIconData($event)" multiple multiselect-search="true" multiselect-select-all="true">
					<option v-for="(programme, i) in this.programme" :value="programme.id">{{ programme.value }}</option>
				</select>
			</form>

			<button @click="this.saveMap" id="mapSave" class="page-button padding-large pb-dark">
				Save Map
				<div>
					<i v-if="this.submitIcon == 'loading'" class="fa-solid fa-spinner fa-spin"></i>
					<i v-else-if="this.submitIcon == 'success'" class="fa-solid fa-check"></i>
				</div>
			</button>
		</div>
	</div>
</template>

<script>
  export default {
    props: [
			'map',
			'icons',
			'programme',
    ],

		data() {
			return {
				active: this.map.active,
				startX: 0,
				startY: 0,
				newX: 0,
				newY: 0,
				targetId: null,
				selectedIcon: null,
				submitIcon:  null,
			};
		},

		mounted() {
			this.map.images.forEach((existingIcon) => this.createExistingIcon(existingIcon));
		},

		methods: {
			async toggleActive() {
				try {
					this.response = await fetch('/admin-mapToggleMap');
					this.result = await this.response.json();
					
				} catch (err) {
					console.log('----ERROR----');
					console.log(err);
				
				} finally {
					this.active = this.result;
				}
			},

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
				newDiv.dataset.angle = 0;
				newDiv.dataset.title = '';
				newDiv.dataset.description = '';
				newDiv.dataset.programme = [];

				let newImg = document.createElement('img');
				newImg.src = event.target.src;

				let deleteButton = document.createElement('i');
				deleteButton.className = 'deleteButton fa-solid fa-trash';
				deleteButton.id = 'deleteButton';

				let sizeHandle = document.createElement('i');
				sizeHandle.className = 'sizeHandle fa-solid fa-up-right-and-down-left-from-center fa-rotate-90';
				sizeHandle.id = 'sizeHandle';

				let angleHandle = document.createElement('i');
				angleHandle.className = 'angleHandle fa-solid fa-turn-down';
				angleHandle.id = 'angleHandle';

				newDiv.appendChild(newImg);
				newDiv.appendChild(deleteButton);
				newDiv.appendChild(sizeHandle);
				newDiv.appendChild(angleHandle);
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
					newDiv.style.transform = `rotate(${existingIcon.angle}deg)` ?? '0deg';
					newDiv.dataset.id = count;
					newDiv.dataset.height = newIconHeight ?? 100;
					newDiv.dataset.width = newIconWidth ?? 100;
					newDiv.dataset.angle = existingIcon.angle ?? 0;
					newDiv.dataset.title = existingIcon.title;
					newDiv.dataset.description = existingIcon.description;
					newDiv.dataset.programme = existingIcon.programme;

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

					let angleHandle = document.createElement('i');
					angleHandle.className = 'angleHandle fa-solid fa-turn-down';
					angleHandle.id = 'angleHandle';

					newDiv.appendChild(newImg);
					newDiv.appendChild(deleteButton);
					newDiv.appendChild(sizeHandle);
					newDiv.appendChild(angleHandle);
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

						let deleteButton = selected.querySelector('#deleteButton');
						deleteButton.style.display = 'none';

						let sizeHandle = selected.querySelector('#sizeHandle');
						sizeHandle.style.display = 'none';

						let angleHandle = selected.querySelector('#angleHandle');
						angleHandle.style.display = 'none';
					}

					let target = event.target.parentElement;

					this.selectedIcon = target.dataset.id;

					target.classList.add('selected');

					let deleteButton = target.querySelector('#deleteButton');
					deleteButton.style.display = 'flex';

					let sizeHandle = target.querySelector('#sizeHandle');
					sizeHandle.style.display = 'flex';

					let angleHandle = target.querySelector('#angleHandle');
					angleHandle.style.display = 'flex';

					let sizeInput = document.querySelector('#mapEditSection input[name="size"]');
					let angleInput = document.querySelector('#mapEditSection input[name="angle"]');
					let titleInput = document.querySelector('#mapEditSection input[name="title"]');
					let descriptionInput = document.querySelector('#mapEditSection textarea[name="description"]');
					let programmeInput = document.querySelector('#mapEditSection select[name="programme"]');

					sizeInput.value = Math.round(target.dataset.height);
					angleInput.value = target.dataset.angle;
					titleInput.value = target.dataset.title;
					descriptionInput.value = target.dataset.description;

					let options = programmeInput.querySelectorAll('option');
					let programmeIds = target.dataset.programme.split(',');

					options.forEach((option) => {
						if (programmeIds.includes(option.value)) {
							option.selected = true;
						} else {
							option.selected = false;
						}
					});

					let programmeOptions = [];

					this.programme.forEach((programme) => {
						if (programmeIds.includes(programme.id.toString())) {
							programmeOptions.push(programme.value);
						}
					});

					let programmeElements = document.querySelectorAll('.multiselect-dropdown-list div:not(.multiselect-dropdown-all-selector)');

					programmeElements.forEach((element) => {
						let input = element.querySelector('input');
						let label = element.querySelector('label');

						if (programmeOptions.includes(label.innerText)) {
							input.checked = true;
							element.classList.add('checked');
						}
					});
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

					} else if (name == 'angle') {
						let target = document.querySelector('.mapIcon.selected');
						target.style.transform = `rotate(${event.target.value}deg)`;

						icon.dataset.angle = event.target.value;

					} else if (name == 'programme') {						
						var newProgramme = [];
						let options = event.target.parentElement.querySelectorAll('.multiselect-dropdown-list .checked:not(.multiselect-dropdown-all-selector)');

						options.forEach((option) => {
							let value = option.querySelector('label').innerText;

							this.programme.forEach((programme) => {
								if (programme.value == value) {
									newProgramme.push(programme.id);
								}
							});
						});

						icon.dataset.programme = newProgramme;

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

				image.style.height = newHeight + "px";
				image.style.width = newWidth + "px";

				return [newHeight, newWidth];
			},

			deleteIcon(event) {
				let target = event.target.parentElement;
				target.remove();

				this.selectedIcon = null;
			},

			mouseDown(event) {
				this.selectIcon(event);

				if (!(event.target.tagName === 'IMG' || event.target.id === 'sizeHandle' || event.target.id === 'angleHandle')) {
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
				} else if (event.target.id === 'angleHandle') {
					document.addEventListener("mousemove", this.rotateMove);
					document.addEventListener("mouseup", this.rotateUp);
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

				image.style.height = newHeight + "px";
				image.style.width = newWidth + "px";

				target.dataset.height = newHeight;
				target.dataset.width = newWidth;
				
				let sizeInput = document.querySelector('#mapEditSection input[name="size"]');
				sizeInput.value = newHeight;
			},

			sizeUp(event) {
				document.removeEventListener("mousemove", this.sizeMove);
				document.removeEventListener("mouseup", this.sizeUp);
			},

			rotateMove(event) {
				let target = document.getElementById(this.targetId);
				let rect = target.getBoundingClientRect();
				let centerX = rect.left + rect.width / 2;
				let centerY = rect.top + rect.height / 2;

				let angle = Math.atan2(event.clientY - centerY, event.clientX - centerX) * (180 / Math.PI);
				angle = angle + 45;

				target.style.transform = `rotate(${angle}deg)`;

				angle = Math.round(angle);

				target.dataset.angle = angle;

				let angleInput = document.querySelector('#mapEditSection input[name="angle"]');
				angleInput.value = angle;
			},

			rotateUp(event) {
				document.removeEventListener("mousemove", this.rotateMove);
				document.removeEventListener("mouseup", this.rotateUp);
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
							angle: icon.dataset.angle,
							position: iconPosition,
							title: icon.dataset.title,
							description: icon.dataset.description,
							programme: icon.dataset.programme
						});
					}
				}

				this.submitIcon = 'loading';

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

					this.submitIcon = null;

				} finally {
					if (this.result == true) {
						this.submitIcon = 'success';
					}
				}
			}
		},
  };
</script>
