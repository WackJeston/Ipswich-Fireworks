<template>
  <section class="banner" id="banner-homepage-top">

		<div id="ticket-button-container">
			<a href="https://ipswichfireworks.ticketsrv.co.uk/" class="title thick-title">BUY TICKETS</a>
		</div>

		<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
			<img rel="preload" :src="this.asset + banner.fileName" :alt="banner.fileName">
			<div class="banner-overlay"></div>
		</div>

    <carousel v-else :items-to-show="1" :wrapAround="true" :autoplay="7000">
      <slide v-for="(banner, i) in banners" :key="slide" class="banner-slide">
				<img v-if="i == 1" rel="preload" :src="this.asset + banner.fileName" :alt="banner.fileName">
				<img v-else defer :src="this.asset + banner.fileName" :alt="banner.fileName">
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
			'asset',
    ],

    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },
  };
</script>
