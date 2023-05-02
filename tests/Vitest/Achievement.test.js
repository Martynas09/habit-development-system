import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Achievements from '../../resources/js/Pages/Achievements.vue';

const data = [
  {
    id: 11,
    title: 'Pirmas planas',
    description: 'Skiriamas už sukurtą pirmajį planą',
    rewardXP: 10,
    created_at: '2023-04-30T12:53:54.000000Z',
    updated_at: '2023-04-30T12:54:01.000000Z',
  },
  {
    id: 12,
    title: '50 užduočių',
    description: 'Skiriamas už 50 atliktų užduočių',
    rewardXP: 50,
    created_at: '2023-04-30T12:53:54.000000Z',
    updated_at: '2023-04-30T12:54:01.000000Z',
  },
  {
    id: 13,
    title: '100 užduočių',
    description: 'Skiriamas už 100 atliktų užduočių',
    rewardXP: 50,
    created_at: '2023-04-30T12:53:54.000000Z',
    updated_at: '2023-04-30T12:54:01.000000Z',
  },
  {
    id: 1,
    title: 'Totam suscipit qui distinctio ullam non',
    description: 'Ut unde aut illum blanditiis. Quibusdam itaque occaecati officia praesentium rem natus asperiores accusantium. Rerum modi et et. Quod in molestiae aut natus accusantium labore.',
    rewardXP: 277,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-25T08:54:09.000000Z',
  },
  {
    id: 2,
    title: 'Et explicabo necessitatibus quo ipsam officia.',
    description: 'Mollitia quas incidunt ut sed quos optio odit quasi. Dolorem illo iste ut voluptatem et illo. Suscipit eum id dolores et. Quo aut iusto quia cumque rerum quae soluta.',
    rewardXP: 573,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 3,
    title: 'Nemo itaque id omnis cumque ea et corrupti.',
    description: 'At maxime quia facilis eos aut. Occaecati quisquam ut error aut blanditiis. Eligendi quia exercitationem sit dolorem ut animi animi.',
    rewardXP: 206,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 4,
    title: 'Voluptatem rerum quisquam maxime doloribus aut at vero.',
    description: 'Commodi qui voluptatum velit repellat sunt accusantium architecto blanditiis. Et dolorem quaerat quasi ad consequatur maiores nihil. Est incidunt vel tempora ad nobis magni.',
    rewardXP: 603,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 5,
    title: 'Ipsam ut sunt odit occaecati vel sit quae.',
    description: 'Vel modi veritatis quam. Unde quasi molestiae quaerat omnis alias voluptatem quasi. Consequuntur molestiae rerum alias sapiente perspiciatis et sint commodi. Voluptate quasi aliquid qui consectetur est voluptate voluptas.',
    rewardXP: 788,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 6,
    title: 'Nobis deserunt ab libero et.',
    description: 'Quisquam voluptatum nemo voluptas. Voluptas quas nihil consequatur sapiente impedit et repellendus voluptates. Odit incidunt neque beatae dolorem rem. Modi sit tempora qui minima.',
    rewardXP: 547,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 7,
    title: 'Velit omnis amet voluptatem dolore.',
    description: 'Molestiae voluptatem veritatis in sint at aut corrupti rerum. Amet optio et fugit in non veniam aut. Sint nulla neque quibusdam reiciendis. Nihil quibusdam harum minima.',
    rewardXP: 743,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 8,
    title: 'Rerum occaecati tenetur suscipit voluptas sit dolor.',
    description: 'Quia facere doloribus cumque. Ratione omnis sed necessitatibus quidem et. Sunt ut rem nulla ut.',
    rewardXP: 957,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 9,
    title: 'Adipisci earum quis ea quas omnis voluptate facere.',
    description: 'Qui enim ratione amet. Est eligendi est et sed. Ullam suscipit animi cupiditate. Rem iusto omnis veritatis aperiam temporibus.',
    rewardXP: 340,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
  {
    id: 10,
    title: 'Voluptatem ut non tempora minus voluptatem delectus harum.',
    description: 'Rem quibusdam dignissimos qui harum id adipisci et. Quibusdam eius eos itaque. Facere ut voluptas dolor. Vitae voluptatem inventore sequi expedita quaerat fugiat.',
    rewardXP: 327,
    created_at: '2023-04-24T07:08:11.000000Z',
    updated_at: '2023-04-24T07:08:11.000000Z',
  },
];

const wrapper = mount(Achievements, {
  props: {
    achievements: data,
  },
});

// test('Mount Achievements', () => {
//   expect(Achievements).toBeTruthy();
//   expect(wrapper.props().data).toStrictEqual(data);
// });

// test('onMounted()', () => {
//   expect(wrapper.vm.activeHead).toBe('head1.png');
//   expect(wrapper.vm.activeTop).toBe('top1.png');
//   expect(wrapper.vm.activeBottom).toBe('bottom1.png');
//   expect(wrapper.vm.activeShoes).toBe('shoes1.png');
// });
