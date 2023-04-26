<template>
    <topheader/>
    <!-- <sidebar/> -->
    <div class="content-wrapper">
        <div class="content">
            <div class="container">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script setup>
import topheader from "./top-header.vue";
import sidebar from "./sidebar.vue";
</script>
