<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item" v-for="(item, index) in menu" :key="index" :class="{ 'active': $route.name == item.name }">
            <router-link class="nav-link" :to="{ name: item.name}" >{{ item.label }}</router-link>
          </li>
        </ul>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data: () => ({
    menu: [
      {
        name: 'home',
        label: 'Home'
      },
      {
        name: 'school',
        label: 'School List'
      }
    ]
  })
};
</script>

<style lang="scss" scoped>
  #menu {
    font-size: 17px;
    text-transform: uppercase;

    .nav-item + .nav-item {
      margin-left: 10px;
    }
  }
</style>
