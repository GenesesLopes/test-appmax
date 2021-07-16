<template>
    <div>
        <navbar />
        <b-container fluid>
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
    mounted() {
        document.querySelector("body").style.background = "transparent";
    }
};
</script>
