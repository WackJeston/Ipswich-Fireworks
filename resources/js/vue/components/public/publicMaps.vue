<template>
	<div class="content">
		<div id="mapInfoContainer">
			<h2></h2>
			<p></p>

			<ul class="programme">
				<li>
					<h3>Name (Stage) time <a href="link">link</a></h3>
				</li>
			</ul>
		</div>

		<div id="mapImageContainer">
			<img :src="this.map.fileName" alt="Map Image" id="mapImage">
		</div>
	</div>
</template>


<script>
  export default {
    props: [
      'map',
    ],
		
		mounted() {
			this.map.images.forEach((existingIcon) => this.createExistingIcon(existingIcon))
		},

		methods: {
			createExistingIcon(existingIcon) {
				let primary = document.getElementById('mapImageContainer');
				let mapImage = primary.querySelector('#mapImage');

				if (mapImage.offsetHeight == 0 || mapImage.offsetWidth == 0) {
					setTimeout(() => {
						this.createExistingIcon(existingIcon);
					}, 100);

				} else {
					let heightRatio = (mapImage.offsetHeight / this.map.canvasHeight);
					let widthRatio = mapImage.offsetWidth / this.map.canvasWidth;

					let newIconPositionTop = existingIcon.position.top * heightRatio;
					let newIconPositionLeft = existingIcon.position.left * widthRatio;
					let newIconHeight = existingIcon.size.height * heightRatio;
					let newIconWidth = existingIcon.size.width * widthRatio;

					let newImg = document.createElement('img');
					newImg.src = existingIcon.asset;
					newImg.className = 'mapIcon';
					newImg.style.width = newIconWidth + 'px' ?? '100px';
					newImg.style.height = newIconHeight + 'px' ?? '100px';
					newImg.style.top = newIconPositionTop + 'px' ?? '0px';
					newImg.style.left = newIconPositionLeft + 'px' ?? '0px';
					newImg.style.transform = `rotate(${existingIcon.angle}deg)` ?? '0deg';
					newImg.dataset.title = existingIcon.title;
					newImg.dataset.description = existingIcon.description;
					newImg.dataset.programme = JSON.stringify(existingIcon.programme);

					primary.appendChild(newImg);

					newImg.addEventListener('click', (event) => {
						let mapInfoContainer = document.getElementById('mapInfoContainer');

						if (event.target.dataset.title != 'null') {
							mapInfoContainer.querySelector('h2').innerText = event.target.dataset.title;
						} else {
							mapInfoContainer.querySelector('h2').innerText = '';
						}

						if (event.target.dataset.description != 'null') {
							mapInfoContainer.querySelector('p').innerText = event.target.dataset.description;
						} else {
							mapInfoContainer.querySelector('p').innerText = '';
						}

						if (event.target.dataset.programme != 'null') {
							let programme = JSON.parse(event.target.dataset.programme);

							let programmeList = mapInfoContainer.querySelector('.programme');
							programmeList.innerHTML = '';

							programme.forEach((item) => {
								let itemElement = document.createElement('li');

								console.log(item.value);

								itemElement.innerHTML = `<h3>${item.value} (${item.stage}) ${item.time} <a href="${item.link}">item.link</a></h3>`;
								programmeList.appendChild(itemElement);
							});
						} else {
							mapInfoContainer.querySelector('.programme').innerHTML = '';
						}
					});
				}
			}
		}
  };
</script>
