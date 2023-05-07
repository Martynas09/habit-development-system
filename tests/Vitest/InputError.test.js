import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import InputError from '../../resources/js/Components/InputError.vue';

const wrapper = mount(InputError);

test('Mount InputError', () => {
  expect(InputError).toBeTruthy();
});
