<template>
  <div>
    <h2 class="text-center">
      {{ user.username }}
    </h2>

    <div>
      <h3>Muted Users</h3>

      <ul>
        <li
          v-for="mutedUser in mutedUsers"
          :key="mutedUser.id"
        >
          {{ mutedUser.username }}
        </li>
      </ul>
    </div>

    <div>
      <h3>Posts</h3>

      <div class="text-right">
        <strong>order by:</strong>

        <a
          v-for="(direction, directionKey) in orders"
          :key="directionKey"
          href="#"
          :class="{'font-weight-bold': directionKey===orderBy}"
          class="d-inline-block mx-1"
          @click="changeOrder(directionKey)"
        >{{ direction }}</a>
      </div>

      <ul>
        <li
          v-for="post in posts"
          :key="post.id"
        >
          [id:{{ post.id }}] {{ post.title }} <strong>by {{ post.ownerName }}</strong>{{ post.created_at }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      currentUser: null,
      mutedUsers: [],
      posts: [],
      orders: {
        'rand': 'rand',
        'desc': 'newest',
        'asc' : 'oldest'
      },
      orderBy: 'rand'
    }
  },

  watch: {
    user() {
      this.updateData()
    }
  },

  created() {
    this.updateData()
  },

  methods: {
    changeOrder(direction) {
      this.orderBy = direction
      this.updateData();
    },

    updateData() {
      this.currentUser = this.user

      this.$http.get(window.route('api.user.detail', {
        user: this.currentUser.id,
        orderBy: this.orderBy
      }))
        .then((response) => {
          this.mutedUsers = response.data.mutedUsers;
          this.posts = response.data.posts;
        })
        .catch((error) => {
          console.error(error)
        });
    }
  }
};
</script>
