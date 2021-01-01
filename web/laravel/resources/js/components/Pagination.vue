<template>
  <ul class="paginationList">
    <li
      v-for="number in frontPageRange"
      :key="number"
      :class="{ currentButton: currentPage == number }"
    >
      <router-link :to="`${routerPath}?page=${number}`">{{ number }}</router-link>
    </li>
    <li v-show="frontDot">…</li>
    <li
      v-for="number in middlePageRange"
      :key="number"
      :class="{ currentButton: currentPage == number }"
      v-show="lastPage>=3"
    >
      <router-link :to="`${routerPath}?page=${number}`">{{ number }}</router-link>
    </li>
    <li v-show="endDot">…</li>
    <li
      v-for="number in endPageRange"
      :key="number"
      :class="{ currentButton: currentPage == number }"
      v-show="lastPage>=9"
    >
      <router-link :to="`${routerPath}?page=${number}`">{{ number }}</router-link>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
    routerPath: String,
  },
  data() {
    return {
      frontDot: false,
      endDot: false,
    };
  },
  computed: {
    isFirstPage() {
      return this.currentPage === 1;
    },
    isLastPage() {
      return this.currentPage === this.lastPage;
    },
    frontPageRange() {
      const numbers = [];
      if (this.lastPage >= 2) {
        numbers.push(1, 2);
      } else {
        numbers.push(1);
      }
      return numbers;
    },
    middlePageRange() {
      const numbers = [];
      const range = 5;
      let start;
      let end;
      if (this.currentPage <= range && this.lastPage >= 9) {
        start = 3;
        end = range + 2;
        this.endDot = true;
      } else if (this.currentPage > this.lastPage - range && this.lastPage >= 9) {
        start = this.lastPage - range - 1;
        end = this.lastPage - 2;
        this.frontDot = true;
      } else if (this.lastPage >= 9) {
        start = this.currentPage - Math.floor(range / 2);
        end = this.currentPage + Math.floor(range / 2);
        this.frontDot = true;
        this.endDot = true;
      } else {
        start = 3;
        end = this.lastPage;
      }
      for (let i = start; i <= end; i++) {
        numbers.push(i);
      }
      return numbers;
    },
    endPageRange() {
      const numbers = [];
      if (this.lastPage >= 9) {
        numbers.push(this.lastPage - 1, this.lastPage);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.paginationList {
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  width: 100%;
  height: 32px;
  margin: 0;
  padding: 0;
  li {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    margin: 0 5px;
    transition-duration: 0.3s;
    a {
      font-size: 14px;
    }
  }
}

.currentButton {
  transition-duration: 0.3s;
  background-color: $maincolor;
  a {
    color: white;
  }
}
</style>
