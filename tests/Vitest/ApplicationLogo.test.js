import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ApplicationLogo from '../../resources/js/Components/ApplicationLogo.vue';

const wrapper = mount(ApplicationLogo);

test('Mount ApplicationLogo', () => {
  expect(ApplicationLogo).toBeTruthy();
});
