<template>
    <div>
        <div style="background-color: whitesmoke;" class="fire-spinner">Logging out...</div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex';

    export default {
        name: "Logout",
        methods: {
            ...mapActions(['Logout']),
        },
        created() {
            this.Logout({path: '/'});
        }
    }
</script>

<style scoped>
    .fire-spinner-covered {
        position: relative;
    }

    .fire-spinner {
        align-items: center;
        background: rgba(255, 255, 255, .64);
        display: flex;
        display: -webkit-box;
        display: -moz-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        flex-direction: column;
        height: 100%;
        -webkit-justify-content: center;
        justify-content: center;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 6;
    }
</style>
