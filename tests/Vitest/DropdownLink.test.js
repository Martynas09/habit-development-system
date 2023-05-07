import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import DropdownLink from '../../resources/js/Components/DropdownLink.vue';

const wrapper = mount(DropdownLink);

test('Mount DropdownLink', () => {
  expect(DropdownLink).toBeTruthy();
});
