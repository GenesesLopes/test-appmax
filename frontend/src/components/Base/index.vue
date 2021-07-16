<template>
    <div>
        <navbar />
        <b-container fluid>
            <h4>{{this.title}}</h4>
            <slot />
        </b-container>
    </div>
</template>
<script>
import Navbar from "../Navbar";
export default {
    components: {
        Navbar
    },
    props:['title'],
    mounted() {
        document.querySelector("body").style.background = "transparent";
    }
};
</script>
