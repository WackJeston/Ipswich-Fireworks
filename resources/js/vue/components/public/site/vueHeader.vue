<template>
	<header>

		<nav id="header-start">
			<a v-if="this.tickets" async href="https://ipswichfireworks.ticketsrv.co.uk/" target="_blank" class="header-button title thick-title"><span>TICKETS</span></a>
		</nav>

		<a class="logo-link" href="/">
			<img async :src="this.publicasset + 'logo-white.png'" alt="logo" class="logo">
		</a>

		<div id="header-end">
			<ul id="header-socials">
				<a v-for="(social, i) in this.socials" :href="social.link" target="_blank">
					<li><i :class="social.icon"></i></li>
				</a>
			</ul>

			<i @click='toggleSiteMenu' class="fa-solid fa-bars" id="nav-menu-button"></i>
		</div>

		<div class="menu-overlay" @click="toggleOverlay"></div>

	</header>
</template>


<script>
export default {
	props: [
		'sitetitle',
		'sitetitlemini',
		'publicasset',
		'publiclinks',
		'socials',
		'sessionuser',
		'tickets',
	],

	data() {
		return {
			mobile: null,
			siteMenu: false,
			windowWidth: null,
			menuActive: false,
			mqLarge: null,
			sublinksActive: false,
			userMenuActive: false,
		}
	},

	methods: {
		toggleSiteMenu() {
			this.siteMenu = !this.siteMenu;
			let menu = document.querySelector(".site-menu");
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
			this.siteMenu = false;
			this.menuActive = false;
			let menu = document.querySelector(".site-menu");
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
