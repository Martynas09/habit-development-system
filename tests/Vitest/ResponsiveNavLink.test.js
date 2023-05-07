import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ResponsiveNavLink from '../../resources/js/Components/ResponsiveNavLink.vue';

const wrapper = mount(ResponsiveNavLink);

test('Mount ResponsiveNavLink', () => {
  expect(ResponsiveNavLink).toBeTruthy();
});
