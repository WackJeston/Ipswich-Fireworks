<template>
  <div class="form-width-control">
		<form action="/feedbackCreateEnquiry">
			<input type="hidden" name="_token" :value="csrf">

			<label for="name">Name<span> *</span></label>
			<input type="text" name="name" required>

			<label for="email">Email<span> *</span></label>
			<input type="email" name="email" required>

			<label for="phone">Phone</label>
			<input type="tel" name="phone">

			<label for="subject">Subject</label>
			<input type="text" name="subject">

			<label for="message">Message<span> *</span></label>
			<textarea name="message" required></textarea>

			<input type="submit" value="Send" class="submit">
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
