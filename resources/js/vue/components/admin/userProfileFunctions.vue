<template>
  <!-- Buttons -->
	<div class="vue-button-row">
		<div>
			
		</div>
		<div>
  		<button class="page-button" type="button" onclick="setShowMarker('edit')" :class="{ 'button-active' : this.show == 'edit' }" @click="this.show == 'edit' ? this.show = false : this.show = 'edit'"><i class="fa-solid fa-pen-to-square"></i>Edit</button>
			<button :onclick="this.deleteLink" class="page-button pb-danger" type="button"><i class="fa-solid fa-trash-can"></i>Delete</button>
		</div>
	</div>

  <!-- Edit -->
  <div v-html="this.editform.html" v-show="this.show == 'edit'" :class="{ 'functions-padding' : this.show == 'edit' }"></div>
</template>


<script>
  export default {
    props: [
			'pageshowmarker',
      'user',
			'editform',
    ],

    data() {
      return {
        show: this.pageshowmarker,
				deleteLink: "showDeleteWarning('user', " + this.user.id + ", '/user-profileDelete/" + this.user.id + "')",
      };
    },
  };
</script>
