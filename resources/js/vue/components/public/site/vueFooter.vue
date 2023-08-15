<template>
  <footer>

    <nav>
      <a href="/" class="title">
        <h2>{{ this.sitetitle }}</h2>
      </a>

      <ul class="lt">
        <a v-for="(link, i) in this.publiclinks" :href="link.link" class="nav-link">
          <li>{{ capFL(link.title) }}</li>
        </a>
      </ul>
    </nav>

		<a href="/">
			<img :src="this.asset + 'logo-white.png'" alt="logo" class="logo">
		</a>

  </footer>
</template>


<script>
  export default {
    props: [
      'sitetitle',
			'asset',
      'publiclinks',
    ],

    methods: {
      capFL(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
    },
  };
</script>
