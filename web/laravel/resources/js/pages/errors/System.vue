<template>
    <div>
        <p>システムエラーが発生しました。</p>
        <router-link to="/" exact>トップページへ戻る</router-link>
    </div>
</template>

<style lang="scss" scoped>
@import "../../../sass/common.scss";
div {
    height: 100px;
}
p {
    text-align: center;
    padding-top: 30px;
    margin: 0;
    margin-bottom: 15px;
}
a {
    opacity: 0.7;
    text-align: center;
    display: inline-block;
    width: 100%;
    transition-duration: 0.3s;
    &:hover {
        opacity: 0.5;
        transition-duration: 0.3s;
    }
}
</style>
