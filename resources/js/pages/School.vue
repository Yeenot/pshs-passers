<template>
    <div id="school">
        <div class="container-fluid content-wrapper">
            <div class="row">
                <div class="col">
                    <h1>2018 PSHS NCE Schools</h1>
                    <ul class="list-group schools">
                        <li class="list-group-item" v-for="(item, index) in schools" :key="index">
                            {{ item.school }}
                            <span class="badge badge-info">{{ item.count }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data: () => ({
        schools: [],
        url: '/api/passers/schools'
    }),

    mounted() {
        this.getSchools();
    },

    methods: {
        getSchools() {
            const parent = this;
            axios.get(`${parent.url}`)
            .then(function(response) {
                if (response.status == 200) {
                    parent.schools = response.data;
                }
            });
        }
    }
}
</script>

<style lang="scss" scoped>
    h1 {
        font-size: 30px;
    }
    
    .schools {
        font-size: 13px;
        max-width: 500px;
        width: 100%;
        margin: 40px auto 0 auto;

        .badge {
            font-size: 11px;
            color: #ffffff;
            margin-left: 10px;
        }
    }
</style>

