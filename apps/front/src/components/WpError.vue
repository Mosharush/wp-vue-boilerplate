<template>
  <div class="wp-error">
    <h1>Error: {{ error.message }}</h1>
    <hr/>
    Try to check if the REST API is enabled in WordPress and all setup is correct.
    <br/>
    <a target="_blank" href="/wp-admin">
      Go to Wordpress site
    </a>
    OR try to set friendly urls on <a target="_blank" href="/wp-admin/options-permalink.php">permalinks in WordPress</a>.
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