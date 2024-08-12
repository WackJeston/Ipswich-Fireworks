<template>
  <section class="banner" id="banner-homepage-top">

		<div id="ticket-button-container">
			<a v-if="this.tickets" href="https://ipswichfireworks.ticketsrv.co.uk/" target="_blank" class="title thick-title">BUY TICKETS</a>
			<span v-else class="title thick-title"><h5>TICKETS AVAILABLE FROM</h5><br>{{ this.ticketdate }}</span>
		</div>

		<div v-if="this.banners.length == 1" v-for="banner in this.banners" class="banner-slide single-slide">
			<img :src="banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
			<div class="banner-overlay"></div>
		</div>

    <carousel v-else v-bind="this.settings">
      <slide v-for="(banner, i) in banners" :key="slide" class="banner-slide">
				<img v-if="i == 1" :src="banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
				<img v-else defer :src="banner.fileName" :alt="banner.fileName" :style="{objectPosition: 'center ' + banner.framing}">
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
	import { defineComponent } from 'vue'
  import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

  import 'vue3-carousel/dist/carousel.css';

	export default defineComponent({
		name: 'Basic',
    components: {
      Carousel,
      Slide,
      Pagination,
      Navigation,
    },

		props: [
      'banners',
			'tickets',
			'ticketdate',
    ],

		data: () => ({
			settings: {
				itemsToShow: 1,
				wrapAround: true,
				autoplay: 6000,
			},
		}),
	});

  // export default {
  //   props: [
  //     'banners',
	// 		'tickets',
	// 		'ticketdate',
  //   ],
  // };
</script>
