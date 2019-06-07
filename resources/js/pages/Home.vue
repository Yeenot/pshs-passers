<template>
  <div id="home">
    <!-- toaster -->
    <toaster id="msg" :title="toaster.title">
      {{ toaster.message }}
    </toaster>
    <!-- modals -->
    <modal :title="'Add New Examinee Passer'" id="modal-add-passer" :onPositive="onSavePasser">
      <form>
        <div class="form-group" v-for="(field, index) in passer" :key="index">
          <label>{{ field.label }}</label>
          <input class="form-control" :type="field.type" v-model="field.value" />
          <span class="error" v-if="field.errors.length > 0">{{ field.errors[0] }}</span>
        </div>
      </form>
    </modal>
    <div class="container-fluid content-wrapper">
      <div class="row">
        <div class="col">
          <h1>2018 PSHS NCE Passers</h1>
          
          <div id="passers-list">
            <div class="actions row">
              <div class="col">
                <input class="form-control search" type="text" v-model="query" v-on:keyup.enter="onSearch()" />
                <button class="btn btn-primary" v-on:click="onSearch()">Search</button>
                <button class="btn btn-danger" v-on:click="onClear()">Clear</button>
              </div>
              <div class="col">
                <button id="add-new-passer" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add-passer">
                  Add New
                </button>
              </div>
            </div>
            <advanced-table :url="api.passers.list"
              :headers="headers" :search="search"></advanced-table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import AdvancedTable from "@/js/components/advanced-table/index";
import Modal from "@/js/components/modal/index";
import Toaster from "@/js/components/toaster/index";

export default {
  components: {
    AdvancedTable,
    Modal,
    Toaster
  },

  data: () => ({
    api: {
      passers: {
        list: '/api/passers/',
        add: '/api/passers/add'
      }
    },
    headers: [
      {
        key: 'id',
        label: 'ID',
        width: '80px'
      },
      {
        key: 'fullname',
        label: 'Examinee',
        width: 'auto'
      },
      {
        key: 'campus_eligibility',
        label: 'Campus Eligibility',
        width: '150px'
      },
      {
        key: 'school',
        label: 'School',
        width: '300px'
      },
      {
        key: 'division',
        label: 'Division',
        width: '230px'
      }
    ],
    query: '',
    search: '',
    passer: [
      {
        type: 'text',
        label: 'First Name & Middle Name',
        key: 'firstname',
        value: '',
        errors: []
      },
      {
        type: 'text',
        label: 'Surname',
        key: 'surname',
        value: '',
        errors: []
      },
      {
        type: 'text',
        label: 'Campus Eligibility',
        key: 'campus_eligibility',
        value: '',
        errors: []
      },
      {
        type: 'text',
        label: 'School',
        key: 'school',
        value: '',
        errors: []
      },
      {
        type: 'text',
        label: 'Division',
        key: 'division',
        value: '',
        errors: []
      }
    ],
    toaster: {
      title: '',
      message: ''
    }
  }),

  methods: {
    onSearch: function() {
      this.search = this.query
    },

    onClear: function() {
      this.query = '';
      this.search = '';
    },

    validate() {
      const parent = this;
      var allow = true;

      for (var ctr = 0; ctr < this.passer.length; ctr++) {
        var field = this.passer[ctr];
        if (field.value == '') {
          if (allow) {
            allow = false;
          }
          field.errors.push('This field is required.');
        }
      }

      return allow;
    },

    clearFields() {
      for (var ctr = 0; ctr < this.passer.length; ctr++) {
        var field = this.passer[ctr];
        field.value = '';
        field.errors = [];
      }
    },

    onSavePasser: function() {
      if (this.validate()) {
        const parent = this;
        var data = {};
        this.passer.forEach(function(field) {
          data[field.key] = field.value;
        });
        axios.post(`${parent.api.passers.add}`, data)
          .then(function(response) {
            if (response.status == 200) {
              $('#modal-add-passer').modal('hide');

              parent.toaster.title = 'Success!';
              parent.toaster.message = 'Examiner has been successfully saved!';
              $('#msg').toast('show');

              parent.clearFields();
            }
        });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
  h1 {
    font-size: 30px;
  }

  #add-new-passer {
    float: right;
  }

  #passers-list {
    margin-top: 30px;
  }

  .actions {
    margin-top: 15px;
    margin-bottom: 20px;
    overflow: hidden;

    .search {
      max-width: 250px;
      margin-right: 10px;
      display: inline-block;

      &:focus {
        outline: none;
      }
    }
  }

  .error {
    font-size: 12px;
    font-style: italic;
    color: #e3342f;
  }
</style>

