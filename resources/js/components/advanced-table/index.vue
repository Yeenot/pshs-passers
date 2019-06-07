<template>
  <div class="advanced-table">
    <table class="table table-striped">
      <thead>
        <tr>
          <th v-for="(header, index) in headers"
              :key="index"
              :style="{ width: header.width}">
            {{ header.label }}
          </th>
        </tr>
      </thead>
    </table>
    <div class="table-body">
      <table class="table table-striped">
        <tbody>
          <tr class="no-records" v-if="list.length == 0">
            <td :colspan="headers.length">No Record/s</td>
          </tr>
          <tr v-for="(item, index) in list"
              :key="index">
            <td v-for="(header, headerId) in headers"
                :key="headerId"
                :style="{ width: header.width }">
              {{ item[header.key] }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="tbl-footer">
      <nav class="pagination-wrapper" v-if="options.total > 0">
        <ul class="pagination">
          <li class="page-item" v-on:click="goToPage(1)"><a class="page-link">&lt;&lt;</a></li>
          <li class="page-item" v-on:click="goToPage(options.page - 1)"><a class="page-link">&lt;</a></li>
          <li class="page-item"><a class="page-link dot" v-if="range[0] > 1">..</a></li>
          <li class="page-item" v-for="(page, index) in range" :key="index" :class="{ 'current': page == options.page }" v-on:click="goToPage(page)">
            <a class="page-link">{{ page }}</a>
          </li>
          <li class="page-item"><a class="page-link dot" v-if="range[range.length - 1] < total">..</a></li>
          <li class="page-item" v-on:click="goToPage(options.page + 1)"><a class="page-link">&gt;</a></li>
          <li class="page-item" v-on:click="goToPage(total)"><a class="page-link">&gt;&gt;</a></li>
        </ul>
      </nav>

      <div class="records" v-if="options.total > 0">
        Showing {{ toNumberString(records - (options.limit - 1)) }} to {{ toNumberString(recordsInPage)  }} records of {{ toNumberString(totalRecords) }} records
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: {
    url: {
      type: String,
      default: null
    },
    headers: {
      type: Array,
      default: () => []
    },
    search: {
      type: String,
      default: ''
    }
  },

  data: () => ({
    list: [],
    options: {
      page: 1,
      limit: 50,
      total: 0,
      search: ''
    }
  }),

  mounted() {
    this.getContent();
  },

  watch: {
    list() {
      var tableBody = this.$el.querySelector('.table-body');
      tableBody.scrollTop = 0;
    },

    search(value) {
      this.options.search = value;
      this.options.page = 1;
      this.getContent();
    }
  },

  computed: {
    records() {
      return this.options.limit * this.options.page;
    },

    total() {
      return Math.ceil(this.options.total / this.options.limit);
    },

    totalRecords() {
      return this.options.total;
    },

    recordsInPage() {
      return (this.records > this.totalRecords) ? this.totalRecords : this.records;
    },

    range() {
      var options = this.options;
      var total = this.total;
      var distance = 2;
      var start = options.page - distance;

      var excess = 0;
      if (start < 1) {
        excess = 1 - start;
      }
      start = start + excess;
      var end = start + 4;
      excess = 0;
      if (end > total) {
        excess = end - total
      }
      start = start - excess;

      var breakpoint = (total < end) ? total : end;
      var pages = [];
      for (var ctr = start; ctr <= breakpoint; ctr++) {
        if (ctr > 0) {
          pages.push(ctr);
        }
      }

      return pages;
    }
  },
  
  methods: {
    toNumberString(value) {
      return value.toLocaleString();
    },
    getContent: function() {
      const parent = this;
      axios.get(`${parent.url}`, {
        params: {
          page: parent.options.page,
          limit: parent.options.limit,
          search: parent.options.search
        }
      }).then(function(response) {
          if (response.status == 200) {
            var data = response.data;
            parent.list = data.list;
            parent.options.page = data.page;
            parent.options.limit = data.limit;
            parent.options.total = data.total;
          }
      });
    },

    goToPage(page) {
      if (this.options.page != page && page >= 1 && page <= this.total) {
        this.options.page = page;
        this.getContent();
      }
    },

    reload() {
      this.getContent();
    }
  }
};
</script>

<style lang="scss" scoped>
  .advanced-table {
    font-size: 11px;

    th, td {
      text-align: center;
    }

    th {
      font-size: 14px;
      text-transform: uppercase;
    }

    .table-body {
      height: 500px;
      overflow-y: auto;
    }

    .tbl-footer {
      margin-top: 20px;
      overflow: hidden;
    }

    .pagination-wrapper {
      float: left;

      .page-item {
        cursor: pointer;

        &:focus {
            outline: none;
          }

        .page-link {
          color: inherit;

          &.dot {
            background-color: transparent;
            border-top-color: transparent;
            border-bottom-color: transparent;
            padding-top: 1rem;
            padding-bottom: 0;
          }
        }

        &.current {
          .page-link {
            background-color: #3490dc;
            color: #ffffff;
          }
        }
      }
    }

    .records {
      float: right;
      font-size: 13px;
    }

    .no-records {
      font-size: 13px;
      font-weight: 700;
    }
  }
</style>
