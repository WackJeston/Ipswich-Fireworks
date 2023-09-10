<template>
	<div class="form-content-container">
		<div class="form-width-control">
			<div class="form-content">
				<h2>Become a sponsor</h2>
				<h3>If you would like to sponsor us, please fill out the form below and we will get back to you as soon as possible</h3>
			</div>

			<form action="/sponsorsCreateEnquiry">
				<input type="hidden" name="_token" :value="csrf">

				<label for="name">Name<span> *</span></label>
				<input type="text" name="name" required>

				<label for="email">Email<span> *</span></label>
				<input type="email" name="email" required>

				<label for="phone">Phone</label>
				<input type="tel" name="phone">

				<label for="message">Message</label>
				<textarea name="message"></textarea>

				<input type="submit" value="Submit" class="submit">
			</form>
		</div>
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
