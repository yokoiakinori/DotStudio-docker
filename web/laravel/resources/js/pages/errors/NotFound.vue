<template>
  <div>
    <p>お探しのページは見つかりませんでした。</p>
    <router-link to="/" exact>トップページへ戻る</router-link>
  </div>
</template>

<style lang="scss" scoped>
div {
  height: 100px;
}
p {
  text-align: center;
  padding-top: 30px;
  margin: 0;
}
a {
  text-align: center;
  display: inline-block;
  width: 100%;
}
</style>
