<template>
	<div id="mapImageContainer">
		<img :src="this.map.fileName" alt="Map Image" id="mapImage">
	</div>
</template>


<script>
  export default {
    props: [
      'map',
    ],
		
		mounted() {
			console.log(this.map);

			this.map.images.forEach((existingIcon) => this.createExistingIcon(existingIcon))
		},

		methods: {
			createExistingIcon(existingIcon) {
				console.log('createExistingIcon');

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

					primary.appendChild(newImg);
				}
			}
		}
  };
</script>
