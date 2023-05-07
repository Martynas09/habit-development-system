import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import TextInput from '../../resources/js/Components/TextInput.vue';

const wrapper = mount(TextInput);

test('Mount TextInput', () => {
  expect(TextInput).toBeTruthy();
});
