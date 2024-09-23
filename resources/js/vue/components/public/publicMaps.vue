<template>
	<div class="content">
		<img :src="this.map.fileName" alt="Map Image">
	</div>
</template>


<script>
  export default {
    props: [
      'map',
    ],
		
		mounted() {
			console.log(this.map);
		}
  };
</script>
