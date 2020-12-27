<template>
  <div>
    <div class="list-group">
      <a
        v-for="user in users"
        :key="user.id"
        :class="{ active: user.id === currentUser }"
        class="list-group-item list-group-item-action"
        href="#"
        @click="selectUser(user)"
      >
        {{ user.username }}
      </a>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      currentUser: null
    };
  },

  created() {
    this.$http.get(window.route('api.user.list'))
      .then((response) => {
        this.users = response.data.users;
      })
      .catch((error) => {
        console.error(error);
      });
  },

  methods: {
    selectUser(user) {
      this.currentUser = user.id;
      this.$emit('change', user);
    }
  }
};
</script>
