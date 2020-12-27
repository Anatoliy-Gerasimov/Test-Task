<template>
  <div class="row">
    <div class="col-4">
      <AppUsersList @change="changeUser" />
    </div>

    <div
      v-if="currentUser"
      class="col-8"
    >
      <AppUserDetail :user="currentUser" />
    </div>
  </div>
</template>

<script>
  import AppUsersList from './AppUsersList';
  import AppUserDetail from './AppUserDetail';

  export default {
    components: {
      AppUsersList,
      AppUserDetail
    },
    data() {
      return {
        currentUser: null
      }
    },
    methods: {
      changeUser(user) {
        this.currentUser = user;
      }
    }
  }
</script>
