<template>
  <img :src="this.map.fileName" alt="Map Image">
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
