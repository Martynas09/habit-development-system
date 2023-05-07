import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import SecondaryButton from '../../resources/js/Components/SecondaryButton.vue';

const wrapper = mount(SecondaryButton);

test('Mount SecondaryButton', () => {
  expect(SecondaryButton).toBeTruthy();
});
