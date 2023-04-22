import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import CharacterItemSelection from '../../resources/js/Components/CharacterItemSelection.vue';

const itemsHead = ['head1.png', 'head2.png', 'head3.png'];
const itemsTop = ['top1.png', 'top2.png', 'top3.png'];
const itemsBottom = ['bottom1.png', 'bottom2.png', 'bottom3.png'];
const itemsShoes = ['shoes1.png', 'shoes2.png', 'shoes3.png'];
const character = {
  head: 'head1.png', top: 'top1.png', bottom: 'bottom1.png', shoes: 'shoes1.png',
};
const wrapper = mount(CharacterItemSelection, {
  props: {
    itemsHead,
    itemsTop,
    itemsBottom,
    itemsShoes,
    character,
  },
});

test('Mount CharacterItemSelection', () => {
  expect(CharacterItemSelection).toBeTruthy();
  expect(wrapper.props().itemsHead).toStrictEqual(itemsHead);
  expect(wrapper.props().itemsTop).toStrictEqual(itemsTop);
  expect(wrapper.props().itemsBottom).toStrictEqual(itemsBottom);
  expect(wrapper.props().itemsShoes).toStrictEqual(itemsShoes);
  expect(wrapper.props().character).toStrictEqual(character);
});

test('onMounted()', () => {
  expect(wrapper.vm.activeHead).toBe('head1.png');
  expect(wrapper.vm.activeTop).toBe('top1.png');
  expect(wrapper.vm.activeBottom).toBe('bottom1.png');
  expect(wrapper.vm.activeShoes).toBe('shoes1.png');
});
