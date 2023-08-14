<template>
  <section class="landing-zone-carousel lt" id="homepage-carousel">

    <carousel :items-to-show="1" :wrapAround="true" :autoplay="7000">

      <slide v-for="banner in banners" :key="slide" class="lz-slide"
      :style="{ backgroundImage: 'url(' + this.asset + banner.fileName + ')' }">
        <h2>{{ banner.title }}</h2>
        <p>{{ banner.subtitle }}</p>
        <div class="lz-overlay"></div>
      </slide>

      <template v-if="this.banners.length > 1" #addons>
        <navigation class="lz-nav"/>
        <pagination class="lz-pagination"/>
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
