import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import PrimaryButton from '../../resources/js/Components/PrimaryButton.vue';

const wrapper = mount(PrimaryButton);

test('Mount PrimaryButton', () => {
  expect(PrimaryButton).toBeTruthy();
});
