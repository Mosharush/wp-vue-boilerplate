<template>
  <div class="home">
    <ul v-if="!error">
      <li v-for="post in posts" :key="post.id">
        <a :href="`post/${post.id}`">{{ post.title.rendered }}</a>
      </li>
    </ul>
    <WpError v-else :error="error" />
  </div>
</template>

<script>
import apiFetch from "@wordpress/api-fetch";
import { ref } from "vue";
import WpError from "../components/WpError.vue";

export default {
  name: "HomeView",
  components: {
    WpError,
  },
  setup() {
    const posts = ref([]);
    const error = ref(null);

    async function fetchPosts() {
      try {
        posts.value = await apiFetch({ path: "/wp-json/wp/v2/posts" });
      } catch (e) {
        error.value = {
          link: `${location.protocol}//${location.hostname}:8000`,
          ...e,
        };
      }
    }
    fetchPosts();

    return {
      posts,
      error,
    };
  },
};
</script>
