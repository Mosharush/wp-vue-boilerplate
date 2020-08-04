<template>
  <article class="single" v-if="postData">
    <h1>{{ postData.title.rendered }}</h1>
    <img
      v-if="postData.image"
      :src="postData.image"
      :alt="postData.title.rendered"
    />
    <div v-html="postData.content.rendered"></div>
  </article>
</template>

<script>
import apiFetch from "@wordpress/api-fetch";

export default {
  name: "Single",
  data() {
    return {
      postData: {
        id: null,
        title: "",
        image: "",
        content: "",
      },
    };
  },
  async beforeMount() {
    const post = await apiFetch({
      path: "/wp/v2/posts/" + this.$route.params.id,
    });

    this.postData = post;
  },
};
</script>

<style></style>
