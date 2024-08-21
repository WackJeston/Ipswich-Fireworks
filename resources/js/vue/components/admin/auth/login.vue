<template>
  <div class="form-container dk">

    <!-- Login Form -->
    <form action="/adminLogin" method="GET" enctype="multipart/form-data" class="web-box">
      <input type="hidden" name="_token" :value="csrf">

      <label for="email">Email<span> *</span></label>
      <input type="email" name="email" required>

			<label for="password">Password<span> *</span></label>
			<label for="password" class="show-password" required>
				<i class="fa-solid fa-eye"></i>
			</label>
			<input class="password-input" type="password" name="password" required autocomplete="one-time-code" />

			<div class="checkbox-container">
				<input type="checkbox" name="remember" id="remember">
				<label for="remember">Remember Me</label>
			</div>

			<div class="bottom-row">
				<a href="/admin/forgot-password" class="display-link">Forgot your Password?</a>
				<input class="submit page-button padding-large" type="submit" name="submit" value="Login">
			</div>
    </form>

  </div>
</template>


<script>
  export default {
    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      }
    },
  };
</script>
