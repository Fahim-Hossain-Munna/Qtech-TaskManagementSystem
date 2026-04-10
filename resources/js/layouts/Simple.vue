<template>
    <div class="wrapper">
        <main class="flex-grow-1 d-flex flex-column">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.wrapper {
    display: flex;
    min-height: 100vh;
    flex-direction: column;
    justify-content: space-between;
}
</style>
