<template>
  <section class="banner" id="banner-homepage-bottom">

		<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
			<img rel="preload" :src="banner.fileName" :alt="banner.fileName">
			<div class="banner-overlay"></div>
		</div>

    <carousel v-else :items-to-show="1" :wrapAround="true" :autoplay="7000">
      <slide v-for="(banner, i) in banners" :key="slide" class="banner-slide">
				<img v-if="i == 1" rel="preload" :src="banner.fileName" :alt="banner.fileName">
				<img v-else defer :src="banner.fileName" :alt="banner.fileName">
        <div class="banner-overlay"></div>
      </slide>

      <template v-if="this.banners.length > 1" #addons>
        <navigation class="banner-nav"/>
        <pagination class="banner-pagination"/>
      </template>
    </carousel>

  </section>
</template>


<script>
  import 'vue3-carousel/dist/carousel.css';
  import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

  export default {
    props: [
      'banners',
    ],

    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },
  };
</script>
