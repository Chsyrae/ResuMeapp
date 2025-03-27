<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  title: { type: String, required: true },
  content: { type: String, required: true },
  modelValue: { type: Boolean, required: true },
  persistent: { type: Boolean, default: true },
});

const emit = defineEmits(["update:modelValue", "confirm", "cancel"]);
</script>
<template>
  <v-dialog
    :model-value="modelValue"
    :persistent="persistent"
     max-width="440"
     @update:modelValue="value => emit('update:modelValue', value)"
  >
    <v-card>
      <v-card-title>{{ title }}</v-card-title>
      <v-divider></v-divider>
      <v-card-text>{{ content }}</v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text color="error"
          @click="$emit('cancel'); $emit('update:modelValue', false)"
        >
          No
        </v-btn>
        <v-btn text color="success"
          @click="$emit('confirm'); $emit('update:modelValue', false)"
        >
          Yes
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>