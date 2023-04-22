import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import CharacterPreview from '../../resources/js/Components/CharacterPreview.vue';

const wrapper = mount(CharacterPreview, {
  props: {
    head: 'head1.png',
    top: 'top1.png',
    bottom: 'bottom1.png',
    shoes: 'shoes1.png',
  },
});

test('Mount CharacterPreview', () => {
  expect(CharacterPreview).toBeTruthy();
  expect(wrapper.props().head).toBe('head1.png');
  expect(wrapper.props().top).toBe('top1.png');
  expect(wrapper.props().bottom).toBe('bottom1.png');
  expect(wrapper.props().shoes).toBe('shoes1.png');
});

test('check if h1 has passed prop', () => {
  expect(wrapper.find('h1').text()).toBe('Jūsų personažas:');
});
