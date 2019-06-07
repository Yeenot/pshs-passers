<template>
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
        <div class="toast-header" :class="{
                'success': status == 200
            }">
            <strong class="mr-auto">{{ title }}</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: null
        },
        status: {
            type: Number,
            default: 200
        },
        toasterId: {
            type: String,
            default: ''
        }
    }
}
</script>

<style lang="scss" scoped>
    .toast {
        position: fixed;
        top: 10px;
        right: 10px;
        left: auto;
        width: 300px;

        .toast-header {
            background-color: #38c172;

            &.success {
                color: #ffffff;
            }
        }

        .toast-body {
                background-color: rgba(255, 255, 255, 0.6);
        }
    }
</style>

