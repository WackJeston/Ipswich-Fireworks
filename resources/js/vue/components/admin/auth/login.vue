<template>
  <div class="login-form dk">

    <!-- Login Form -->
    <form action="/adminLogin" method="GET" enctype="multipart/form-data" class="web-box">
      <input type="hidden" name="_token" :value="csrf">

      <label for="email">Email<span> *</span></label>
      <input type="email" name="email" required>

      <label for="password">Password<span> *</span></label>
      <label for="password" class="show-password" required>
        <i id="show-password" v-show="!showLoginPassword" @click="(this.showLoginPassword = !this.showLoginPassword)" class="fa-solid fa-eye"></i>
        <i id="hide-password" v-show="showLoginPassword" @click="(this.showLoginPassword = !this.showLoginPassword)" class="fa-regular fa-eye-slash"></i>
      </label>
      <input class="password" :type="!showLoginPassword ? 'password' : 'text'" name="password" required autocomplete="one-time-code">

			<div class="checkbox-container">
				<input type="checkbox" name="remember" id="remember">
				<label for="remember">Remember Me</label>
			</div>

			<div class="bottom-row">
				<a href="/admin-password-reset" class="display-link">Forgot your Password?</a>
				<input class="submit page-button padding-large" type="submit" name="submit" value="Login">
			</div>
    </form>

    <!-- <a href="/admin-registration">Register</a> -->

  </div>
</template>


<script>
  export default {
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        showLoginPassword: false,
      }
    },
  };
</script>
