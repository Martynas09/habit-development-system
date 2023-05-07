import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Modal from '../../resources/js/Components/Modal.vue';

const wrapper = mount(Modal);

test('Mount Modal', () => {
  expect(Modal).toBeTruthy();
});
