<template>
  <div class="home">
    <ul>
      <li v-for="post in posts" :key="post.id">
        <a :href="`post/${post.id}`">{{ post.title.rendered }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
import apiFetch from "@wordpress/api-fetch";

export default {
  name: "Home",
  components: {},
  data() {
    return {
      posts: [],
    };
  },
  async beforeMount() {
    this.posts = await apiFetch({ path: "/wp-json/wp/v2/posts" });
  },
};
</script>
