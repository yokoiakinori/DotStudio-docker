<template>
    <div>{{ searchKeyword }}</div>
</template>

<script>
import { mapState } from "vuex";
export default {
    computed: mapState({
        searchKeyword: state => state.search.keyword
    })
};
</script>

<style></style>
