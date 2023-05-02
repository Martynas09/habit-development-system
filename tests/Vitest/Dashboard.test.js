import { test, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Dashboard from '../../resources/js/Pages/Dashboard.vue';

const dataTasks = [
  {
    id: 108,
    execution_date: '2023-05-01 11:00:00',
    is_done: 0,
    fk_reminder: null,
    fk_task: 71,
    fk_plan: 24,
    created_at: '2023-04-27T09:10:53.000000Z',
    updated_at: '2023-04-27T09:10:53.000000Z',
    get_task: {
      id: 71,
      duration: 30,
      title: 'Išmokti naują dalyką',
      created_at: '2023-04-27T09:10:53.000000Z',
      updated_at: '2023-04-27T09:10:53.000000Z',
    },
  },
  {
    id: 100,
    execution_date: '2023-05-02 14:00:00',
    is_done: 0,
    fk_reminder: null,
    fk_task: 70,
    fk_plan: 24,
    created_at: '2023-04-27T09:10:52.000000Z',
    updated_at: '2023-04-27T09:10:52.000000Z',
    get_task: {
      id: 70,
      duration: 30,
      title: 'Treniruotė',
      created_at: '2023-04-27T09:10:52.000000Z',
      updated_at: '2023-04-27T09:10:52.000000Z',
    },
  },
  {
    id: 104,
    execution_date: '2023-05-04 06:00:00',
    is_done: 0,
    fk_reminder: null,
    fk_task: 70,
    fk_plan: 24,
    created_at: '2023-04-27T09:10:52.000000Z',
    updated_at: '2023-04-27T09:10:52.000000Z',
    get_task: {
      id: 70,
      duration: 30,
      title: 'Treniruotė',
      created_at: '2023-04-27T09:10:52.000000Z',
      updated_at: '2023-04-27T09:10:52.000000Z',
    },
  },
];
const dataNotes = [
  {
    id: 6,
    description: 'Ab similique cum est ea culpa magnam illo.',
    fk_user: 1,
    created_at: '2023-04-24T07:08:12.000000Z',
    updated_at: '2023-04-24T07:08:12.000000Z',
  },
  {
    id: 26,
    description: 'Est sint deleniti et explicabo voluptate ad.',
    fk_user: 1,
    created_at: '2023-04-24T07:08:12.000000Z',
    updated_at: '2023-04-24T07:08:12.000000Z',
  },
];
const AuthenticatedLayoutMock = {
  template: '<div><slot name="header"></slot><slot></slot></div>',
};
const HeadMock = {
  template: '<div></div>',
};
const wrapper = mount(Dashboard, {
  props: {
    tasks: dataTasks,
    notes: dataNotes,
  },
  mocks: {
    AuthenticatedLayout: AuthenticatedLayoutMock,
    Head: HeadMock,
  },
});
test('Mount Achievements', () => {
  expect(Dashboard).toBeTruthy();
});
