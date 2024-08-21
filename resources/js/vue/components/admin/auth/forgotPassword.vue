<template>
  <div class="form-container dk">

    <!-- Login Form -->
    <form action="/adminForgotPasswordEmail/" method="POST" enctype="multipart/form-data" class="web-box">
      <input type="hidden" name="_token" :value="csrf">
			
			<small>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</small>

      <label for="email">Email<span> *</span></label>
      <input type="email" name="email" required>

			<div class="bottom-row">
				<input class="submit page-button padding-large" type="submit" name="submit" value="Email Password Reset Link">
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
