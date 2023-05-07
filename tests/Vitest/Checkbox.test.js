import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Checkbox from '../../resources/js/Components/Checkbox.vue';

const wrapper = mount(Checkbox);

test('Mount Checkbox', () => {
  expect(Checkbox).toBeTruthy();
});
