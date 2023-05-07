import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import UserStats from '../../resources/js/Components/UserStats.vue';

const wrapper = mount(UserStats);

test('Mount UserStats', () => {
  expect(UserStats).toBeTruthy();
});
test('renders the component', () => {
  expect(wrapper.exists()).toBe(true);
});
test('displays the correct level', () => {
  expect(wrapper.vm.displayLevel).toBe(1);
});

test('displays the correct left side value', () => {
  expect(wrapper.vm.displayLeftSide).toBe(0);
});

test('displays the correct right side value', () => {
  expect(wrapper.vm.displayRightSide).toBe(100);
});

test('displays the correct percent', () => {
  expect(wrapper.vm.displayPercent).toBe(0);
});
test('displays the correct level image', () => {
  expect(wrapper.find('img').attributes('src')).toBe('/storage/level1.png');
});
test('displays the progress bar with the correct props', () => {
  const progressBar = wrapper.findComponent({ name: 'a-progress' });
  expect(progressBar.props('percent')).toBe(0);
  expect(progressBar.props('size')).toBe('small');
  expect(progressBar.props('status')).toBe('active');
  expect(progressBar.props('showInfo')).toBe(false);
});
