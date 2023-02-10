<template>
  <div class="wp-error">
    <h1>Error: {{ error.message }}</h1>
    <hr/>
    Try to check if the REST API is enabled in WordPress and all setup is correct.
    <br/>
    <a v-if="error.link" target="_blank" :href="error.link">
      Go to Wordpress site
    </a>
  </div>
</template>

<script>
export default {
  name: "WpError",
  props: {
    error: {
      type: Object,
      required: true,
    },
  },
};
</script>