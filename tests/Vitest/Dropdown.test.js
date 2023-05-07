import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Dropdown from '../../resources/js/Components/Dropdown.vue';

const wrapper = mount(Dropdown);

test('Mount Dropdown', () => {
  expect(Dropdown).toBeTruthy();
});
