import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import DangerButton from '../../resources/js/Components/DangerButton.vue';

const wrapper = mount(DangerButton);

test('Mount DangerButton', () => {
  expect(DangerButton).toBeTruthy();
});
