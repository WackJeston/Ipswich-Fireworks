<template>
  <section class="banner" id="banner-homepage-top">

		<div id="ticket-button-container">
			<a v-if="this.tickets" href="https://ipswichfireworks.ticketsrv.co.uk/" target="_blank" class="title thick-title">BUY TICKETS</a>
			<span v-else class="title thick-title"><h5>TICKETS AVAILABLE FROM</h5><br>{{ this.ticketdate }}</span>
		</div>

		<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
			<img rel="preload" :src="this.asset + banner.fileName" :alt="banner.fileName">
			<div class="banner-overlay"></div>
		</div>

    <carousel v-else :items-to-show="1" :wrapAround="true" :autoplay="6000">
      <slide v-for="(banner, i) in banners" :key="slide" class="banner-slide">
				<img v-if="i == 1" rel="preload" :src="this.asset + banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
				<img v-else defer :src="this.asset + banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
        <div class="banner-overlay"></div>
				<h3 class="banner-title">{{ banner.title }}</h3>
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
			'tickets',
			'ticketdate',
    ],

    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },
  };
</script>
