<template>
  <div class="form-container dk">

    <!-- Login Form -->
    <form :action="'/adminResetPassword/' + this.email + '/' + this.token" method="POST" enctype="multipart/form-data" class="web-box">
      <input type="hidden" name="_token" :value="csrf">

      <label for="password">Password<span> *</span></label>
			<label for="password" class="show-password" required>
				<i class="fa-solid fa-eye"></i>
			</label>
			<input class="password-input" type="password" name="password" required autocomplete="one-time-code" @click="this.alerts = []" />

			<label for="confirm-password">Confirm Password<span> *</span></label>
			<label for="confirm-password" class="show-password" required>
				<i class="fa-solid fa-eye"></i>
			</label>
			<input class="password-input" type="password" name="confirm-password" required autocomplete="one-time-code" @click="this.alerts = []" />

			<div class="form-errors">
				<small v-show="this.alerts != []" v-for="(alert, i) in this.alerts" class="text-red">
					{{ alert }}
				</small>
			</div>

			<div class="bottom-row">
				<input class="submit page-button padding-large" type="submit" name="submit" value="Reset Password" @click="this.checkMatching">
			</div>
    </form>

  </div>
</template>


<script>
  export default {
		props: [
			'email',
			'token',
		],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				alerts: [],
      }
    },

		methods: {
			checkMatching(event) {
				this.alerts = []
				
				let password1 = document.querySelector('input[name="password"]').value;
				let password2 = document.querySelector('input[name="confirm-password"]').value;

				if (password1 !== password2) {
					event.preventDefault();
					this.alerts.push('Passwords do not match');
				}

				if (password1.length < 8) {
					event.preventDefault();
					this.alerts.push('Password must be at least 8 characters long');
				}
			}
		}
  };
</script>
