<script setup>
defineProps({
  type: {
    type: String,
    default: 'submit',
  },
});
</script>

<template>
    <a-button htmlType="submit" type="danger">
        <slot />
    </a-button>
</template>
