import axios from 'axios';
import { ref } from 'vue';

const xp = ref(0);
export default () => {
  const pullXP = async () => {
    await axios.get('http://127.0.0.1:8000/getUserXp').then((response) => {
      xp.value = response.data;
    });
  };
  return {
    pullXP,
    xp,
  };
};
