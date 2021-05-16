<template>
    <app-layout>
        <SectionOne/>
        <blog-menu/>
        <blog-all/>
    </app-layout>
</template>

<script>
    import SectionOne from "../components/blog/SectionOne";

    export default {
        name: "Blogs",
        components: {
            SectionOne
        },
    }
</script>
