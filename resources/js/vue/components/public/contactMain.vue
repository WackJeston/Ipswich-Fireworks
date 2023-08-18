<template>
  <div class="content">
    <ul v-if="((this.contact['email'].length > 0) || (this.contact['phone'].length > 0) || (this.contact['url'].length > 0))" class="contact-details">
      <li><h3>Contact</h3></li>
      <li v-for="(email, i) in this.contact['email']">{{ email.value }}</li>
      <li v-for="(phone, i) in this.contact['phone']">{{ phone.value }}</li>
			<div v-for="(url, i) in this.contact['url']">
				<li><small><strong>{{ url.label }}</strong></small></li>
				<li><a :href="url.value" target="_blank">{{ url.value }}</a></li>
			</div>
    </ul>
  </div>

	<div class="form-width-control">	
		<div class="form-content">
			<h2>Enquiry Form</h2>
			<p>Our organisation is a small team of volunteers and we will do our best to get back to you as soon as possible.</p>
		</div>

		<form action="/contactCreateEnquiry">
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
    props: [
      'contact',
    ],

    data() {
      return {
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      }
    },
  };
</script>
