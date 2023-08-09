<template>
	<header class="dk">

		<nav id="header-start">
			
		</nav>

		<a href="/">
			<!-- <h2 id="header-title">{{ this.sitetitle }}</h2>
			<h2 id="header-title-mini">{{ this.sitetitlemini }}</h2> -->
			<img :src="this.logo" alt="logo" class="logo">
		</a>

		<div id="header-end">
			<i @click='toggleMobileNav' class="fa-solid fa-bars hover-background" id="nav-menu-button"></i>
		</div>

		<div class="menu-overlay" @click="toggleOverlay"></div>

	</header>
</template>


<script>
export default {
	props: [
		'sitetitle',
		'sitetitlemini',
		'logo',
		'publiclinks',
		'userlinks',
		'sessionuser',
	],

	data() {
		return {
			mobile: null,
			mobileNav: false,
			windowWidth: null,
			menuActive: false,
			mqLarge: null,
			sublinksActive: false,
			userMenuActive: false,
		}
	},

	methods: {
		toggleMobileNav() {
			this.mobileNav = !this.mobileNav;
			let menu = document.querySelector(".mobile-nav");
			let overlay = document.querySelector(".menu-overlay");

			if (this.menuActive == false) {
				menu.classList.remove("menu-non-active");
				menu.classList.add("menu-active");

				overlay.style.display = "block";
				overlay.style.opacity = 0.6;

				this.menuActive = true;
			}
			else {
				menu.classList.remove("menu-active");
				menu.classList.add("menu-non-active");

				overlay.style.display = "none";
				overlay.style.opacity = 0;
			};


		},

		toggleOverlay() {
			this.mobileNav = false;
			this.menuActive = false;
			let menu = document.querySelector(".mobile-nav");
			let overlay = document.querySelector(".menu-overlay");

			menu.classList.remove("menu-active");
			menu.classList.add("menu-non-active");

			overlay.style.display = "none";
			overlay.style.opacity = 0;

			return;
		},

		capFL(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
	},
};
</script>
