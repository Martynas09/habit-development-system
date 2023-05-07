import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import InputLabel from '../../resources/js/Components/InputLabel.vue';

const wrapper = mount(InputLabel);

test('Mount InputLabel', () => {
  expect(InputLabel).toBeTruthy();
});
