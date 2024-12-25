<template>
    <button
        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
        type="button"
        @click="$emit('click')"
    >
        <slot />
    </button>
</template>

<script>
export default {
    name: 'RedButton',
};
</script>
