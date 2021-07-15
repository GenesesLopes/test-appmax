<template>
    <div>
        <navbar />
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
